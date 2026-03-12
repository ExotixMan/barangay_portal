<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\BlotterReport;
use App\Models\Witness;
use App\Models\ReportFile;

class BlotterController extends Controller
{
    public function index()
    {
        if (session('submitted_application')) {
            return redirect()->route('incident_success', [
                'reference' => session('reference_number')
            ]);
        }

        return view('barangay_system.incident_form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'reportType' => 'required|string',
            'incidentDate' => 'required|date',
            'incidentTime' => 'required',
            'incidentLocation' => 'required|string|max:255',
            'incidentDescription' => 'required|string',
            'complainantName' => 'required|string|max:255',
            'complainantContact' => 'required|string|max:11',
            'complainantAddress' => 'required|string|max:255',
            'complainantEmail' => 'nullable|email',

            // RESPONDENT OPTIONAL
            'respondentName' => 'nullable|string|max:255',
            'respondentContact' => 'nullable|string|max:11',
            'respondentAddress' => 'nullable|string|max:255',
            'respondentDescription' => 'nullable|string',

            'confidentiality' => 'required|string',

            // EVIDENCE OPTIONAL
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'videos.*' => 'nullable|mimes:mp4,avi,mov|max:51200',
            'documents.*' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        DB::beginTransaction();

        try {

            // generate reference number
            $reference = 'BL-' . date('Y') . '-' . strtoupper(uniqid());

            $report = BlotterReport::create([
                'reference_number' => $reference,
                'report_type' => $request->reportType,
                'incident_date' => $request->incidentDate,
                'incident_time' => $request->incidentTime,
                'location' => $request->incidentLocation,
                'description' => $request->incidentDescription,
                'immediate_action' => $request->immediateAction,
                'complainant_name' => $request->complainantName,
                'complainant_contact' => $request->complainantContact,
                'complainant_address' => $request->complainantAddress,
                'complainant_email' => $request->complainantEmail,
                'respondent_name' => $request->respondentName,
                'respondent_contact' => $request->respondentContact,
                'respondent_address' => $request->respondentAddress,
                'respondent_description' => $request->respondentDescription,
                'confidentiality' => $request->confidentiality,
                'additional_info' => $request->additionalInfo,
                'status' => 'processing',
            ]);

            // save witnesses
            if ($request->witnesses) {
                foreach ($request->witnesses as $w) {
                    if (!empty($w['name']) || !empty($w['statement'])) {
                        Witness::create([
                            'blotter_report_id' => $report->id,
                            'name' => $w['name'] ?? null,
                            'contact' => $w['contact'] ?? null,
                            'statement' => $w['statement'] ?? null,
                        ]);
                    }
                }
            }

            // handle uploads
            $this->uploadFiles($request, $report);

            DB::commit();

            $complaint_name = $request->complainantName;
            $date_submitted = now()->format('F d, Y');

            session([
                'complaint_name' => $complaint_name,
                'date_submitted' => $date_submitted,
                'status' => 'Submitted for Investigation',
                'reference_number' => $reference,
                'submitted_application' => true
            ]);

            return redirect()->route('incident_success', [
                'reference' => $reference
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors($e->getMessage());
        }
    }

    private function uploadFiles($request, $report)
    {
        // PHOTOS
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {

                $ext = $photo->getClientOriginalExtension();
                $name = time() . '_' . uniqid() . '_photo.' . $ext;

                $photo->move(public_path('uploads/evidences/photos'), $name);

                ReportFile::create([
                    'blotter_report_id' => $report->id,
                    'file_type' => 'photo',
                    'file_path' => 'uploads/evidences/photos/' . $name,
                ]);
            }
        }

        // VIDEOS
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {

                $ext = $video->getClientOriginalExtension();
                $name = time() . '_' . uniqid() . '_video.' . $ext;

                $video->move(public_path('uploads/evidences/videos'), $name);

                ReportFile::create([
                    'blotter_report_id' => $report->id,
                    'file_type' => 'video',
                    'file_path' => 'uploads/evidences/videos/' . $name,
                ]);
            }
        }

        // DOCUMENTS
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {

                $ext = $doc->getClientOriginalExtension();
                $name = time() . '_' . uniqid() . '_document.' . $ext;

                $doc->move(public_path('uploads/evidences/documents'), $name);

                ReportFile::create([
                    'blotter_report_id' => $report->id,
                    'file_type' => 'document',
                    'file_path' => 'uploads/evidences/documents/' . $name,
                ]);
            }
        }
    }

    public function success($reference)
    {
        return view('barangay_system.success_form.incident_success', [
            'reference' => $reference,
            'complaint_name' => session('complaint_name'),
            'date_submitted' => session('date_submitted'),
            'status' => session('status'),
        ]);
    }
}
