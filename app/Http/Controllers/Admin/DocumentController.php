<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IndigencyApplication;
use App\Models\BarangayClearance;
use App\Models\ResidencyApplication;
use App\Models\RequestRecord;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    public function document()
    {
        return view('admin.adocumentdoc');
    }

    public function generate(Request $request)
    {
        $request_id = $request->query('request_id');
        $action = $request->query('action', 'download');

        $record = RequestRecord::findOrFail($request_id);

        $templatePath = public_path('document_template/Doc2.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Word template not found');
        }

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('FULL_NAME', $record->full_name);
        $templateProcessor->setValue('DATE_ISSUED', Carbon::parse($record->date_submitted)->format('F d, Y'));

        $fileName = 'certificate_' . $record->request_id . '.docx';
        $outputDir = storage_path('app/generated');
        if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);

        $docxPath = $outputDir . '/' . $fileName;
        $templateProcessor->saveAs($docxPath);

        if ($action === 'print') {
            $phpWord = IOFactory::load($docxPath);
            $objWriter = IOFactory::createWriter($phpWord, 'HTML');

            $tempHtml = $outputDir . '/temp.html';
            $objWriter->save($tempHtml);

            $htmlContent = file_get_contents($tempHtml);
            $pdf = Pdf::loadHTML($htmlContent);
            $pdfFileName = 'certificate_' . $record->request_id . '.pdf';
            $pdfPath = $outputDir . '/' . $pdfFileName;
            $pdf->save($pdfPath);

            return $this->renderPrintPreviewPage($pdfFileName);
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

    public function generateIndigencyOnly(Request $request)
    {
        $id = $request->query('id');
        $action = $request->query('action', 'download');

        $record = IndigencyApplication::findOrFail($id);

        $templatePath = public_path('document_template/Indigency_Template.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Indigency Word template not found');
        }

        // Build full name properly - only include non-empty parts
        $nameParts = array_filter([
            $record->first_name,
            $record->middle_name,
            $record->last_name,
            $record->suffix
        ]);
        $fullName = strtoupper(trim(implode(' ', $nameParts)));
        
        $createdDate = Carbon::parse($record->created_at);

        // Get staff/admin name from the admin guard model fields.
        $adminUser = auth('admin')->user();
        $resolvedStaffName = $adminUser?->full_name
            ?? trim(($adminUser?->first_name ?? '') . ' ' . ($adminUser?->last_name ?? ''))
            ?? $adminUser?->username
            ?? 'BARANGAY STAFF';
        $staffName = strtoupper(trim($resolvedStaffName));
        
        // Extract day, month, year for the certificate format (all uppercase)
        $day = strtoupper($createdDate->format('jS')); // e.g., "23RD"
        $month = strtoupper($createdDate->format('F'));  // e.g., "MARCH"
        $year = strtoupper($createdDate->format('Y'));   // e.g., "2026"
        
        // Prepare full address (uppercase)
        $address = strtoupper($record->address ?? 'N/A');
        
        // Prepare purpose (uppercase)
        $purpose = $record->purpose == 'other' ? strtoupper($record->purpose_other ?? 'N/A') : strtoupper(str_replace('_', ' ', $record->purpose ?? 'N/A'));

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('FULL_NAME', $fullName);
        $templateProcessor->setValue('ADDRESS', $address);
        $templateProcessor->setValue('PURPOSE', $purpose);
        $templateProcessor->setValue('DAY', $day);
        $templateProcessor->setValue('MONTH', $month);
        $templateProcessor->setValue('YEAR', $year);
        $templateProcessor->setValue('STAFF_NAME', $staffName);

        $fileName = 'indigency_' . $record->reference_number . '.docx';
        $outputDir = storage_path('app/generated');
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $docxPath = $outputDir . '/' . $fileName;
        $templateProcessor->saveAs($docxPath);

        if ($action === 'print') {
            // Use LibreOffice conversion only to preserve original DOCX layout
            $pdfFileName = 'indigency_' . $record->reference_number . '.pdf';
            $pdfPath = $outputDir . '/' . $pdfFileName;

            // Remove stale PDF to avoid serving old conversion results.
            if (file_exists($pdfPath)) {
                @unlink($pdfPath);
            }

            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
            $bins = $isWindows
                ? [
                    'soffice',
                    'C:\\Program Files\\LibreOffice\\program\\soffice.exe',
                    'C:\\Program Files (x86)\\LibreOffice\\program\\soffice.exe',
                ]
                : ['libreoffice', 'soffice'];

            $converted = false;
            foreach ($bins as $bin) {
                $cmd = escapeshellarg($bin)
                    . ' --headless --convert-to pdf --outdir '
                    . escapeshellarg($outputDir) . ' ' . escapeshellarg($docxPath) . ' 2>&1';

                $output = [];
                $code = 1;
                @exec($cmd, $output, $code);

                if ($code === 0 && file_exists($pdfPath)) {
                    $converted = true;
                    break;
                }
            }

            if ($converted) {
                return $this->renderPrintPreviewPage($pdfFileName);
            }

            $this->logConversionFailure('indigency', $record->reference_number, $cmd, $code, $output);
            return response()->download($docxPath)->deleteFileAfterSend(true);
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

    public function generateClearanceOnly(Request $request)
    {
        $id = $request->query('id');
        $action = $request->query('action', 'download');

        $record = BarangayClearance::findOrFail($id);

        $templatePath = public_path('document_template/Clearance_Template.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Clearance Word template not found');
        }

        $nameParts = array_filter([
            $record->first_name,
            $record->middle_name,
            $record->last_name,
            $record->suffix
        ]);
        $fullName = strtoupper(trim(implode(' ', $nameParts)));

        $createdDate = Carbon::parse($record->created_at);

        $adminUser = auth('admin')->user();
        $resolvedStaffName = $adminUser?->full_name
            ?? trim(($adminUser?->first_name ?? '') . ' ' . ($adminUser?->last_name ?? ''))
            ?? $adminUser?->username
            ?? 'BARANGAY STAFF';
        $staffName = strtoupper(trim($resolvedStaffName));

        $day = strtoupper($createdDate->format('jS'));
        $month = strtoupper($createdDate->format('F'));
        $year = strtoupper($createdDate->format('Y'));

        $address = strtoupper($record->address ?? 'N/A');
        $purpose = $record->purpose === 'other'
            ? strtoupper($record->purpose_other ?? 'N/A')
            : strtoupper(str_replace('_', ' ', $record->purpose ?? 'N/A'));

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('FULL_NAME', $fullName);
        $templateProcessor->setValue('ADDRESS', $address);
        $templateProcessor->setValue('PURPOSE', $purpose);
        $templateProcessor->setValue('DAY', $day);
        $templateProcessor->setValue('MONTH', $month);
        $templateProcessor->setValue('YEAR', $year);
        $templateProcessor->setValue('STAFF_NAME', $staffName);

        $fileName = 'clearance_' . $record->reference_number . '.docx';
        $outputDir = storage_path('app/generated');
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $docxPath = $outputDir . '/' . $fileName;
        $templateProcessor->saveAs($docxPath);

        if ($action === 'print') {
            $pdfFileName = 'clearance_' . $record->reference_number . '.pdf';
            $pdfPath = $outputDir . '/' . $pdfFileName;

            if (file_exists($pdfPath)) {
                @unlink($pdfPath);
            }

            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
            $bins = $isWindows
                ? [
                    'soffice',
                    'C:\\Program Files\\LibreOffice\\program\\soffice.exe',
                    'C:\\Program Files (x86)\\LibreOffice\\program\\soffice.exe',
                ]
                : ['libreoffice', 'soffice'];

            $converted = false;
            foreach ($bins as $bin) {
                $cmd = escapeshellarg($bin)
                    . ' --headless --convert-to pdf --outdir '
                    . escapeshellarg($outputDir) . ' ' . escapeshellarg($docxPath) . ' 2>&1';

                $output = [];
                $code = 1;
                @exec($cmd, $output, $code);

                if ($code === 0 && file_exists($pdfPath)) {
                    $converted = true;
                    break;
                }
            }

            if ($converted) {
                return $this->renderPrintPreviewPage($pdfFileName);
            }

            $this->logConversionFailure('clearance', $record->reference_number, $cmd, $code, $output);
            return response()->download($docxPath)->deleteFileAfterSend(true);
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

    public function generateResidencyOnly(Request $request)
    {
        $id = $request->query('id');
        $action = $request->query('action', 'download');

        $record = ResidencyApplication::findOrFail($id);

        $templatePath = public_path('document_template/Residency_Template.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Residency Word template not found');
        }

        $nameParts = array_filter([
            $record->first_name,
            $record->middle_name,
            $record->last_name,
            $record->suffix
        ]);
        $fullName = strtoupper(trim(implode(' ', $nameParts)));

        $createdDate = Carbon::parse($record->created_at);

        $adminUser = auth('admin')->user();
        $resolvedStaffName = $adminUser?->full_name
            ?? trim(($adminUser?->first_name ?? '') . ' ' . ($adminUser?->last_name ?? ''))
            ?? $adminUser?->username
            ?? 'BARANGAY STAFF';
        $staffName = strtoupper(trim($resolvedStaffName));

        $day = strtoupper($createdDate->format('jS'));
        $month = strtoupper($createdDate->format('F'));
        $year = strtoupper($createdDate->format('Y'));

        $address = strtoupper($record->address ?? 'N/A');
        $purpose = $record->purpose === 'other'
            ? strtoupper($record->purpose_other ?? 'N/A')
            : strtoupper(str_replace('_', ' ', $record->purpose ?? 'N/A'));

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('FULL_NAME', $fullName);
        $templateProcessor->setValue('ADDRESS', $address);
        $templateProcessor->setValue('PURPOSE', $purpose);
        $templateProcessor->setValue('DAY', $day);
        $templateProcessor->setValue('MONTH', $month);
        $templateProcessor->setValue('YEAR', $year);
        // Keep both keys for template compatibility.
        $templateProcessor->setValue('STAFF_NAME', $staffName);

        $fileName = 'residency_' . $record->reference_number . '.docx';
        $outputDir = storage_path('app/generated');
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $docxPath = $outputDir . '/' . $fileName;
        $templateProcessor->saveAs($docxPath);

        if ($action === 'print') {
            $pdfFileName = 'residency_' . $record->reference_number . '.pdf';
            $pdfPath = $outputDir . '/' . $pdfFileName;

            if (file_exists($pdfPath)) {
                @unlink($pdfPath);
            }

            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
            $bins = $isWindows
                ? [
                    'soffice',
                    'C:\\Program Files\\LibreOffice\\program\\soffice.exe',
                    'C:\\Program Files (x86)\\LibreOffice\\program\\soffice.exe',
                ]
                : ['libreoffice', 'soffice'];

            $converted = false;
            foreach ($bins as $bin) {
                $cmd = escapeshellarg($bin)
                    . ' --headless --convert-to pdf --outdir '
                    . escapeshellarg($outputDir) . ' ' . escapeshellarg($docxPath) . ' 2>&1';

                $output = [];
                $code = 1;
                @exec($cmd, $output, $code);

                if ($code === 0 && file_exists($pdfPath)) {
                    $converted = true;
                    break;
                }
            }

            if ($converted) {
                return $this->renderPrintPreviewPage($pdfFileName);
            }

            $this->logConversionFailure('residency', $record->reference_number, $cmd, $code, $output);
            return response()->download($docxPath)->deleteFileAfterSend(true);
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

    public function previewGeneratedPdf(string $file)
    {
        $safeFile = basename($file);

        if (!preg_match('/^[A-Za-z0-9._-]+\\.pdf$/', $safeFile)) {
            abort(404);
        }

        $pdfPath = storage_path('app/generated/' . $safeFile);

        if (!file_exists($pdfPath)) {
            abort(404, 'Print file not found');
        }

        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Print Document.pdf"'
        ]);
    }

    private function renderPrintPreviewPage(string $pdfFileName)
    {
        return view('admin.print_document', [
            'pdfUrl' => route('admin.documents.preview_file', ['file' => $pdfFileName])
        ]);
    }

    private function logConversionFailure(string $documentType, string $referenceNumber, string $cmd, int $code, array $output): void
    {
        Log::error('LibreOffice conversion failed', [
            'document_type' => $documentType,
            'reference_number' => $referenceNumber,
            'command' => $cmd,
            'exit_code' => $code,
            'output' => implode("\n", $output),
        ]);
    }

}
