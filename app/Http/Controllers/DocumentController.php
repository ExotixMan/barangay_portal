<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestRecord;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function document(){
        return view('barangay_system.adocumentdoc');
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

            return $pdf->stream('certificate.pdf');
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }

}
