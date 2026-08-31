<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\BlotterReport;
use App\Models\Witness;
use App\Models\ReportFile;
use App\Support\NotifiesAdmins;
use App\Services\SupabaseStorageService;

class BlotterController extends Controller
{
    use NotifiesAdmins;

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
        $request->merge([
            'complainantContact' => preg_replace('/\D+/', '', (string) $request->input('complainantContact')),
            'respondentContact' => preg_replace('/\D+/', '', (string) $request->input('respondentContact')),
        ]);

        $request->validate([
            'reportType' => 'required|in:dispute,security,public,other',
            'incidentDate' => 'required|date|before_or_equal:today',
            'incidentTime' => 'required|date_format:H:i',
            'incidentLocation' => 'required|string|min:3|max:255',
            'incidentDescription' => 'required|string|min:10|max:2000',
            'immediateAction' => 'nullable|string|max:1000',
            'complainantName' => ['required','string','min:2','max:255',"regex:/^[A-Za-z\s\-\.',]+$/"],
            'complainantContact' => ['required','regex:/^09\d{9}$/'],
            'complainantAddress' => 'required|string|min:5|max:255',
            'complainantEmail' => 'required|email:rfc',
            'respondentName' => ['nullable','string','min:2','max:255',"regex:/^[A-Za-z\s\-\.',]+$/"],
            'respondentContact' => ['nullable','regex:/^09\d{9}$/'],
            'respondentAddress' => 'nullable|string|min:5|max:255',
            'respondentDescription' => 'nullable|string|max:1000',
            'confidentiality' => 'required|in:low,medium,high',
            'additionalInfo' => 'nullable|string|max:1000',
            'witnesses' => 'nullable|array|max:10',
            'witnesses.*.name' => ['nullable','string','min:2','max:255',"regex:/^[A-Za-z\s\-\.',]+$/"],
            'witnesses.*.contact' => ['nullable','regex:/^09\d{9}$/'],
            'witnesses.*.statement' => 'nullable|string|max:1000',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'videos' => 'nullable|array|max:5',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/x-msvideo,video/quicktime|max:51200',
            'documents' => 'nullable|array|max:10',
            'documents.*' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ], [
            'incidentDate.before_or_equal' => 'Incident date cannot be in the future.',
            'complainantContact.regex' => 'Complainant contact must be a valid 11-digit number starting with 09.',
            'respondentContact.regex' => 'Respondent contact must be a valid 11-digit number starting with 09.',
            'witnesses.*.contact.regex' => 'Witness contact must be a valid 11-digit number starting with 09.',
            'confidentiality.in' => 'Please select a valid confidentiality level.',
        ]);

        DB::beginTransaction();

        try {

            $reference = 'BL-' . date('Y') . '-' . Str::upper(Str::random(20));

            $report = BlotterReport::create([
                'user_id' => $request->user()?->id,
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

            $this->uploadFiles($request, $report);

            DB::commit();

            $this->notifyAdminsOfSubmission(
                category: 'incident',
                title: 'New incident report',
                message: $report->complainant_name . ' submitted ' .
                    $report->report_type . ' (' . $reference . ').',
                url: route('admin.blotter.index', ['highlight' => $report->id]),
                icon: 'fas fa-triangle-exclamation',
            );

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
            Log::error('Incident report submission failed', ['exception' => $e]);
            return back()->withInput()->withErrors(['submission' => 'Something went wrong while submitting your report. Please try again.']);
        }
    }

    private function uploadFiles(Request $request, BlotterReport $report)
    {
        $groups = [
            'photos' => ['type' => 'photo', 'folder' => 'private/evidences/photos'],
            'videos' => ['type' => 'video', 'folder' => 'private/evidences/videos'],
            'documents' => ['type' => 'document', 'folder' => 'private/evidences/documents'],
        ];

        foreach ($groups as $field => $config) {
            if (!$request->hasFile($field)) {
                continue;
            }

            foreach ($request->file($field) as $file) {
                $extension = strtolower((string) $file->getClientOriginalExtension());
                $name = $config['type'] . '_' . Str::uuid() . '.' . $extension;
                $storedPath = app(SupabaseStorageService::class)->storePrivateFileWithName(
                    $file,
                    str_replace('private/', '', $config['folder']),
                    $name
                );

                ReportFile::create([
                    'blotter_report_id' => $report->id,
                    'file_type' => $config['type'],
                    'file_path' => $storedPath,
                ]);
            }
        }
    }

    public function success(string $reference)
    {
        return view('barangay_system.success_form.incident_success', [
            'reference' => $reference,
            'complaint_name' => session('complaint_name'),
            'date_submitted' => session('date_submitted'),
            'status' => session('status'),
        ]);
    }
}
