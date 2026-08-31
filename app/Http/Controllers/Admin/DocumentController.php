<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DocumentTemplateStorageService;
use Illuminate\Http\Request;
use App\Models\IndigencyApplication;
use App\Models\BarangayClearance;
use App\Models\ResidencyApplication;
use App\Models\RequestRecord;
use App\Services\AdminSettingsService;
use App\Services\DocumentPdfService;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    public function __construct(
        private AdminSettingsService $settings,
        private DocumentPdfService $documentPdf
    ) {
    }

    public function document()
    {
        return view('admin.adocumentdoc');
    }

    public function generate(Request $request)
    {
        $request_id = $request->query('request_id');
        $action = $request->query('action', 'download');

        $record = RequestRecord::findOrFail($request_id);

        $templateText = trim((string) $this->settings->get('document.request_template', ''));
        if ($templateText !== '') {
            return $this->generateCustomRequestDocument($record, $templateText, $action);
        }

        $templatePath = app(DocumentTemplateStorageService::class)->path('Doc2.docx');

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
            $pdfFileName = 'certificate_' . $record->request_id . '.pdf';
            return $this->convertForPrint($docxPath, $pdfFileName);
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

    private function generateCustomRequestDocument(RequestRecord $record, string $templateText, string $action)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginTop' => 900,
            'marginBottom' => 900,
            'marginLeft' => 900,
            'marginRight' => 900,
        ]);

        $settings = $this->settings->all();
        $title = (string) data_get($settings, 'document.request_title', 'Barangay Request Certificate');
        $footer = (string) data_get($settings, 'document.request_footer', 'Prepared by Barangay Hulo Administration');

        $section->addText($title, ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $section->addTextBreak(1);
        $section->addText('Request ID: ' . $record->request_id, ['bold' => true]);
        $section->addText('Request Type: ' . ucfirst((string) $record->request_type));
        $section->addText('Submitted On: ' . Carbon::parse($record->date_submitted)->format('F d, Y'));
        $section->addTextBreak(1);

        $renderedTemplate = $this->renderTemplateText($templateText, $record, $footer);
        foreach (preg_split('/\R{2,}/', trim($renderedTemplate)) as $paragraph) {
            $paragraph = trim($paragraph);
            if ($paragraph !== '') {
                $section->addText($paragraph);
                $section->addTextBreak(1);
            }
        }

        $fileName = 'certificate_' . $record->request_id . '.docx';
        $outputDir = storage_path('app/generated');
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $docxPath = $outputDir . '/' . $fileName;
        IOFactory::createWriter($phpWord, 'Word2007')->save($docxPath);

        if ($action === 'print') {
            $pdfFileName = 'certificate_' . $record->request_id . '.pdf';
            return $this->convertForPrint($docxPath, $pdfFileName);
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

    private function renderTemplateText(string $templateText, RequestRecord $record, string $footer): string
    {
        $replacements = [
            '{{full_name}}' => (string) data_get($record, 'full_name', 'N/A'),
            '{{request_type}}' => (string) data_get($record, 'request_type', 'N/A'),
            '{{request_id}}' => (string) data_get($record, 'request_id', 'N/A'),
            '{{date_submitted}}' => Carbon::parse((string) data_get($record, 'date_submitted', now()))->format('F d, Y'),
            '{{complete_address}}' => (string) data_get($record, 'complete_address', 'N/A'),
            '{{contact_number}}' => (string) data_get($record, 'contact_number', 'N/A'),
            '{{email_address}}' => (string) data_get($record, 'email_address', 'N/A'),
            '{{purpose_of_request}}' => (string) data_get($record, 'purpose_of_request', 'N/A'),
            '{{remarks}}' => (string) data_get($record, 'remarks', 'N/A'),
            '{{footer}}' => $footer,
        ];

        return strtr($templateText, $replacements);
    }

    public function generateIndigencyOnly(Request $request)
    {
        $id = $request->query('id');
        $action = $request->query('action', 'download');

        $record = IndigencyApplication::findOrFail($id);

        $templatePath = app(DocumentTemplateStorageService::class)->path('Indigency_Template.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Indigency Word template not found');
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
        $resolvedStaffName = trim((string) ($adminUser?->full_name ?? ''));
        if ($resolvedStaffName === '') {
            $resolvedStaffName = trim(($adminUser?->first_name ?? '') . ' ' . ($adminUser?->last_name ?? ''));
        }
        if ($resolvedStaffName === '') {
            $resolvedStaffName = trim((string) ($adminUser?->username ?? ''));
        }
        $staffName = strtoupper($resolvedStaffName !== '' ? $resolvedStaffName : 'BARANGAY STAFF');

        $day = strtoupper($createdDate->format('jS')); 
        $month = strtoupper($createdDate->format('F'));  
        $year = strtoupper($createdDate->format('Y'));   

        $address = strtoupper($record->address ?? 'N/A');

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
            $pdfFileName = 'indigency_' . $record->reference_number . '.pdf';
            return $this->convertForPrint($docxPath, $pdfFileName);
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

    public function generateClearanceOnly(Request $request)
    {
        $id = $request->query('id');
        $action = $request->query('action', 'download');

        $record = BarangayClearance::findOrFail($id);

        $templatePath = app(DocumentTemplateStorageService::class)->path('Clearance_Template.docx');

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
        $resolvedStaffName = trim((string) ($adminUser?->full_name ?? ''));
        if ($resolvedStaffName === '') {
            $resolvedStaffName = trim(($adminUser?->first_name ?? '') . ' ' . ($adminUser?->last_name ?? ''));
        }
        if ($resolvedStaffName === '') {
            $resolvedStaffName = trim((string) ($adminUser?->username ?? ''));
        }
        $staffName = strtoupper($resolvedStaffName !== '' ? $resolvedStaffName : 'BARANGAY STAFF');

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
            return $this->convertForPrint($docxPath, $pdfFileName);
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

    public function generateResidencyOnly(Request $request)
    {
        $id = $request->query('id');
        $action = $request->query('action', 'download');

        $record = ResidencyApplication::findOrFail($id);

        $templatePath = app(DocumentTemplateStorageService::class)->path('Residency_Template.docx');

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
        $resolvedStaffName = trim((string) ($adminUser?->full_name ?? ''));
        if ($resolvedStaffName === '') {
            $resolvedStaffName = trim(($adminUser?->first_name ?? '') . ' ' . ($adminUser?->last_name ?? ''));
        }
        if ($resolvedStaffName === '') {
            $resolvedStaffName = trim((string) ($adminUser?->username ?? ''));
        }
        $staffName = strtoupper($resolvedStaffName !== '' ? $resolvedStaffName : 'BARANGAY STAFF');

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

        $fileName = 'residency_' . $record->reference_number . '.docx';
        $outputDir = storage_path('app/generated');
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $docxPath = $outputDir . '/' . $fileName;
        $templateProcessor->saveAs($docxPath);

        if ($action === 'print') {
            $pdfFileName = 'residency_' . $record->reference_number . '.pdf';
            return $this->convertForPrint($docxPath, $pdfFileName);
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

    private function convertForPrint(string $docxPath, string $pdfFileName)
    {
        $pdfPath = storage_path('app/generated/' . basename($pdfFileName));

        try {
            $this->documentPdf->convertDocxToPdf($docxPath, $pdfPath);
        } catch (\Throwable $e) {
            Log::error('Document PDF conversion failed', [
                'docx' => basename($docxPath),
                'pdf' => basename($pdfFileName),
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with(
                'error',
                'The document was created, but PDF printing is unavailable. ' . $e->getMessage()
            );
        }

        return $this->renderPrintPreviewPage($pdfFileName);
    }

    private function renderPrintPreviewPage(string $pdfFileName)
    {
        return view('admin.print_document', [
            'pdfUrl' => route('admin.documents.preview_file', ['file' => $pdfFileName])
        ]);
    }

}
