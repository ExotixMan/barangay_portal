<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlotterReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Models\Witness;
use App\Models\ReportFile;
use Carbon\Carbon;
use App\Models\StatusRemarkHistory;
use App\Mail\Notification as NotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IncidentReportController extends Controller
{
    public function index(Request $request)
    {
        $query = BlotterReport::withTrashed();

        $search = preg_replace('/[^a-zA-Z0-9\s\-@.]/', '', $request->search ?? ''); // FIXED: added null coalescing

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('complainant_name', 'like', "%{$search}%")
                  ->orWhere('respondent_name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }
        // Filter by Type
        if ($request->report_type && in_array($request->report_type, ['dispute', 'security', 'public', 'other'])) {
            $query->where('report_type', $request->report_type);
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = strtolower((string) $request->get('direction', 'desc'));
        $direction = $direction === 'asc' ? 'asc' : 'desc';

        $allowedSorts = [
            'reference_number',
            'created_at',
            'report_type',
            'incident_date',
            'complainant_name',
            'confidentiality',
            'status',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if ($sort === 'status') {
            $query->orderByRaw("CASE status WHEN 'processing' THEN 1 WHEN 'resolved' THEN 2 WHEN 'dropped' THEN 3 ELSE 4 END {$direction}");
        } elseif ($sort === 'confidentiality') {
            $query->orderByRaw("CASE confidentiality WHEN 'low' THEN 1 WHEN 'medium' THEN 2 WHEN 'high' THEN 3 ELSE 4 END {$direction}");
        } else {
            $query->orderBy($sort, $direction);
        }

        // Stable secondary sort to avoid row jumps when values are equal.
        $query->orderBy('created_at', 'desc');

        $total_count = BlotterReport::withTrashed()->count();
        $processing_count = BlotterReport::where('status', 'processing')->count();
        $resolved_count = BlotterReport::where('status', 'resolved')->count();
        $dropped_count = BlotterReport::where('status', 'dropped')->count();

        $incidents = $query->with(['witnesses', 'files'])->paginate(20);

        return view('admin.admin_incident_report', compact('incidents', 'total_count', 'processing_count', 'resolved_count', 'dropped_count'));
    }

    public function store(Request $request)
    {
        $this->normalizeIncidentInputs($request);

        $request->validate($this->incidentValidationRules(), $this->incidentValidationMessages());

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

            return redirect()->route('admin.blotter.index') // FIXED: added redirect with proper route
                ->with('success', 'Incident Report added successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Failed to submit application. Please try again. ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $this->normalizeIncidentInputs($request);

        $request->validate($this->incidentValidationRules(), $this->incidentValidationMessages());

        DB::beginTransaction();

        try {
            $report = BlotterReport::findOrFail($id);

            // Update main report
            $report->update([
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
            ]);

            // Update witnesses - delete existing and add new ones
            if ($request->witnesses) {
                // Delete existing witnesses
                Witness::where('blotter_report_id', $report->id)->delete();
                
                // Add new witnesses
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

            // handle new file uploads
            $this->uploadFiles($request, $report);

            DB::commit();

            return back()->with('success', 'Incident Report updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Failed to update report. Please try again. ' . $e->getMessage());
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

    private function incidentValidationRules(): array
    {
        return [
            'reportType' => 'required|in:dispute,security,public,other',
            'incidentDate' => 'required|date|before_or_equal:today',
            'incidentTime' => 'required|date_format:H:i',
            'incidentLocation' => 'required|string|min:3|max:255',
            'incidentDescription' => 'required|string|min:10|max:2000',
            'immediateAction' => 'nullable|string|max:1000',
            'complainantName' => [
                'required',
                'string',
                'min:2',
                'max:255',
                "regex:/^[A-Za-z\\s\\-\\.',]+$/",
            ],
            'complainantContact' => ['required', 'regex:/^09\d{9}$/'],
            'complainantAddress' => 'required|string|min:5|max:255',
            'complainantEmail' => 'required|email:rfc',

            // Respondent fields are optional, but validated if provided.
            'respondentName' => [
                'nullable',
                'string',
                'min:2',
                'max:255',
                "regex:/^[A-Za-z\\s\\-\\.',]+$/",
            ],
            'respondentContact' => ['nullable', 'regex:/^09\d{9}$/'],
            'respondentAddress' => 'nullable|string|min:5|max:255',
            'respondentDescription' => 'nullable|string|max:1000',

            'confidentiality' => 'required|in:low,medium,high',
            'additionalInfo' => 'nullable|string|max:1000',

            'witnesses' => 'nullable|array',
            'witnesses.*.name' => [
                'nullable',
                'string',
                'min:2',
                'max:255',
                "regex:/^[A-Za-z\\s\\-\\.',]+$/",
            ],
            'witnesses.*.contact' => ['nullable', 'regex:/^09\d{9}$/'],
            'witnesses.*.statement' => 'nullable|string|max:1000',

            // Evidence uploads
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'videos' => 'nullable|array',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/x-msvideo,video/quicktime|max:51200',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ];
    }

    private function incidentValidationMessages(): array
    {
        return [
            'reportType.in' => 'Please select a valid report type.',
            'incidentDate.before_or_equal' => 'Incident date cannot be in the future.',
            'incidentTime.date_format' => 'Incident time must be in HH:MM format.',
            'incidentDescription.min' => 'Incident description must be at least 10 characters.',
            'complainantName.regex' => 'Complainant name can only contain letters, spaces, apostrophes, commas, periods, and hyphens.',
            'respondentName.regex' => 'Respondent name can only contain letters, spaces, apostrophes, commas, periods, and hyphens.',
            'witnesses.*.name.regex' => 'Witness name can only contain letters, spaces, apostrophes, commas, periods, and hyphens.',
            'complainantContact.regex' => 'Complainant contact must be a valid 11-digit number starting with 09.',
            'respondentContact.regex' => 'Respondent contact must be a valid 11-digit number starting with 09.',
            'witnesses.*.contact.regex' => 'Witness contact must be a valid 11-digit number starting with 09.',
            'confidentiality.in' => 'Please select a valid confidentiality level.',
        ];
    }

    private function normalizeIncidentInputs(Request $request): void
    {
        $request->merge([
            'complainantContact' => $this->normalizeContactNumber($request->input('complainantContact')),
            'respondentContact' => $this->normalizeContactNumber($request->input('respondentContact')),
        ]);

        $witnesses = $request->input('witnesses', []);
        if (is_array($witnesses)) {
            foreach ($witnesses as $index => $witness) {
                if (is_array($witness) && array_key_exists('contact', $witness)) {
                    $witnesses[$index]['contact'] = $this->normalizeContactNumber($witness['contact']);
                }
            }
            $request->merge(['witnesses' => $witnesses]);
        }
    }

    private function normalizeContactNumber($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', (string) $value);
        if ($digits === '') {
            return null;
        }

        // Convert +63XXXXXXXXXX or 63XXXXXXXXXX into local 09XXXXXXXXX format.
        if (str_starts_with($digits, '63') && strlen($digits) === 12) {
            $digits = '0' . substr($digits, 2);
        }

        return $digits;
    }

    public function approve(Request $request, $id)
    {
        $blotter = BlotterReport::findOrFail($id);
        $request->validate([
            'remarks' => 'nullable|string|max:40',
        ]);

        $remarks = trim((string) $request->input('remarks', ''));
        $oldStatus = $blotter->status;
        $blotter->update(['status' => 'resolved']);

        StatusRemarkHistory::create([
            'request_type' => 'incident',
            'request_id' => $blotter->id,
            'reference_number' => $blotter->reference_number,
            'status' => 'resolved',
            'remarks' => $remarks !== '' ? $remarks : 'Status updated by admin.',
            'channel' => 'system',
            'recipient' => null,
            'admin_user_id' => auth('admin')->id(),
        ]);

        $deliveryResults = $this->sendIncidentStatusNotifications($request, $blotter, 'resolved', $remarks);

        // Log approval
        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'APPROVAL_INCIDENT_REPORT',
                'module' => 'Blotter',
                'details' => [
                    'blotter_id' => $blotter->id,
                    'reference_number' => $blotter->reference_number,
                    'old_status' => $oldStatus,
                    'new_status' => 'resolved',
                    'approved_by' => auth('admin')->user()?->full_name ?? 'Admin',
                    'reporter' => $blotter->created_by,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        $deliverySummary = $this->formatIncidentDeliverySummary($deliveryResults);
        return back()->with('success', 'Incident report resolved. Notifications: ' . $deliverySummary . '.');
    }

    public function markProcessing(Request $request, $id)
    {
        $blotter = BlotterReport::findOrFail($id);
        $request->validate([
            'remarks' => 'nullable|string|max:40',
        ]);

        $remarks = trim((string) $request->input('remarks', ''));
        $oldStatus = $blotter->status;
        $blotter->update(['status' => 'processing']);

        StatusRemarkHistory::create([
            'request_type' => 'incident',
            'request_id' => $blotter->id,
            'reference_number' => $blotter->reference_number,
            'status' => 'processing',
            'remarks' => $remarks !== '' ? $remarks : 'Status set to processing by admin.',
            'channel' => 'system',
            'recipient' => null,
            'admin_user_id' => auth('admin')->id(),
        ]);

        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'PROCESS_INCIDENT_REPORT',
                'module' => 'Blotter',
                'details' => [
                    'blotter_id' => $blotter->id,
                    'reference_number' => $blotter->reference_number,
                    'old_status' => $oldStatus,
                    'new_status' => 'processing',
                    'updated_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return back()->with('success', 'Incident report set to processing successfully.');
    }

    public function reject(Request $request, $id)
    {
        $blotter = BlotterReport::findOrFail($id);
        $request->validate([
            'remarks' => 'required|string|max:40',
        ]);

        $remarks = trim((string) $request->input('remarks', ''));
        $oldStatus = $blotter->status;
        $blotter->update(['status' => 'dropped']);

        StatusRemarkHistory::create([
            'request_type' => 'incident',
            'request_id' => $blotter->id,
            'reference_number' => $blotter->reference_number,
            'status' => 'dropped',
            'remarks' => $remarks,
            'channel' => 'system',
            'recipient' => null,
            'admin_user_id' => auth('admin')->id(),
        ]);

        $deliveryResults = $this->sendIncidentStatusNotifications($request, $blotter, 'dropped', $remarks);

        // Log rejection
        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'REJECT_INCIDENT_REPORT',
                'module' => 'Blotter',
                'details' => [
                    'blotter_id' => $blotter->id,
                    'reference_number' => $blotter->reference_number,
                    'old_status' => $oldStatus,
                    'new_status' => 'dropped',
                    'rejected_by' => auth('admin')->user()?->full_name ?? 'Admin',
                    'reporter' => $blotter->created_by,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        $deliverySummary = $this->formatIncidentDeliverySummary($deliveryResults);
        return back()->with('success', 'Incident report dropped. Notifications: ' . $deliverySummary . '.');
    }

    private function sendIncidentStatusNotifications(Request $request, BlotterReport $blotter, string $status, string $remarks): array
    {
        $results = ['email' => 'not selected', 'sms' => 'not selected'];
        $statusLabel = ucfirst($status);
        $remarksText = $remarks !== '' ? ' Remarks: ' . $remarks : '';

        if ($request->boolean('notify_email')) {
            if (empty($blotter->complainant_email)) {
                $results['email'] = 'failed (missing email)';
            } else {
                try {
                    $message = "Your incident report (Ref: {$blotter->reference_number}) status is now {$statusLabel}.{$remarksText}";
                    Mail::to($blotter->complainant_email)->send(new NotificationMail($blotter->complainant_name ?? 'Complainant', $message));

                    $this->storeIncidentNotificationHistory($blotter, $status, $remarks !== '' ? $remarks : 'Email status update sent.', 'email', $blotter->complainant_email);
                    $results['email'] = 'sent';
                } catch (\Throwable $e) {
                    Log::warning('Failed sending incident status email.', ['error' => $e->getMessage(), 'id' => $blotter->id]);
                    $results['email'] = 'failed';
                }
            }
        }

        if ($request->boolean('notify_sms')) {
            if (empty($blotter->complainant_contact)) {
                $results['sms'] = 'failed (missing mobile number)';
            } else {
                try {
                    $recipient = preg_replace('/\D/', '', (string) $blotter->complainant_contact);
                    if (substr($recipient, 0, 1) === '0') {
                        $recipient = '63' . substr($recipient, 1);
                    } elseif (substr($recipient, 0, 1) === '9') {
                        $recipient = '63' . $recipient;
                    }

                    $smsMessage = "Incident {$blotter->reference_number}: status {$statusLabel}.{$remarksText}";
                    $response = Http::asForm()->post('https://semaphore.co/api/v4/messages', [
                        'apikey' => env('SMS_API_KEY'),
                        'number' => $recipient,
                        'message' => $smsMessage,
                        'sendername' => env('SMS_SENDER_NAME'),
                    ]);

                    if ($response->successful()) {
                        $this->storeIncidentNotificationHistory($blotter, $status, $remarks !== '' ? $remarks : 'SMS status update sent.', 'sms', $recipient);
                        $results['sms'] = 'sent';
                    } else {
                        $results['sms'] = 'failed';
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed sending incident status SMS.', ['error' => $e->getMessage(), 'id' => $blotter->id]);
                    $results['sms'] = 'failed';
                }
            }
        }

        return $results;
    }

    private function storeIncidentNotificationHistory(BlotterReport $blotter, string $status, string $remarks, string $channel, ?string $recipient): void
    {
        try {
            StatusRemarkHistory::create([
                'request_type' => 'incident',
                'request_id' => $blotter->id,
                'reference_number' => $blotter->reference_number,
                'status' => $status,
                'remarks' => $remarks,
                'channel' => $channel,
                'recipient' => $recipient,
                'admin_user_id' => auth('admin')->id(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed storing incident notification history.', ['error' => $e->getMessage(), 'id' => $blotter->id]);
        }
    }

    private function formatIncidentDeliverySummary(array $results): string
    {
        return 'Email ' . ($results['email'] ?? 'not selected') . ', SMS ' . ($results['sms'] ?? 'not selected');
    }

    public function destroy($id)
    {
        $report = BlotterReport::findOrFail($id);
        $reference = $report->reference_number;
        $report->delete();

        if (auth('admin')->check()) {
            $request = request();
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'APPLICATION_ARCHIVED',
                'module' => 'Blotter',
                'details' => [
                    'blotter_id' => $report->id,
                    'reference_number' => $reference,
                    'archived_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return back()->with('success', 'Incident report deleted.');
    }

    public function restore($id)
    {
        $report = BlotterReport::withTrashed()->findOrFail($id);
        $reference = $report->reference_number;
        $report->restore();

        if (auth('admin')->check()) {
            $request = request();
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'APPLICATION_RESTORED',
                'module' => 'Blotter',
                'details' => [
                    'blotter_id' => $report->id,
                    'reference_number' => $reference,
                    'restored_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
        
        return back()->with('success', 'Incident report restored successfully.');
    }

    public function forceDelete($id)
    {
        $report = BlotterReport::withTrashed()->findOrFail($id);
        
        // Delete associated files from storage
        foreach ($report->files as $file) {
            $filePath = public_path($file->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $file->forceDelete(); // Permanently delete file records
        }
        
        // Permanently delete witnesses
        $report->witnesses()->forceDelete();
        
        // Permanently delete the report
        $report->forceDelete();
        
        return back()->with('success', 'Incident report permanently deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:blotter_reports,id'
        ]);

        BlotterReport::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected records archived.');
    }

    public function bulkRestore(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:blotter_reports,id'
        ]);

        BlotterReport::withTrashed()
            ->whereIn('id', $request->ids)
            ->restore();

        return back()->with('success', 'Selected records restored successfully.');
    }

    public function bulkForceDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:blotter_reports,id'
        ]);

        $reports = BlotterReport::withTrashed()
            ->whereIn('id', $request->ids)
            ->get();
        
        foreach ($reports as $report) {
            // Delete associated files from storage
            foreach ($report->files as $file) {
                $filePath = public_path($file->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $file->forceDelete();
            }
            
            // Permanently delete witnesses
            $report->witnesses()->forceDelete();
        }
        
        // Permanently delete reports
        BlotterReport::withTrashed()
            ->whereIn('id', $request->ids)
            ->forceDelete();

        return back()->with('success', 'Selected records permanently deleted.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'exists:blotter_reports,id'
        ]);

        $query = BlotterReport::with(['witnesses', 'files']);

        if ($request->ids) {
            $query->whereIn('id', $request->ids);
        }

        $incidents = $query->get();

        $filename = 'incident_reports_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($incidents) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // CSV Header Row
            fputcsv($file, [
                'ID',
                'Reference Number',
                'Report Type',
                'Incident Date',
                'Incident Time',
                'Location',
                'Description',
                'Immediate Action',
                'Complainant Name',
                'Complainant Contact',
                'Complainant Address',
                'Complainant Email',
                'Respondent Name',
                'Respondent Contact',
                'Respondent Address',
                'Respondent Description',
                'Confidentiality',
                'Additional Info',
                'Status',
                'Deleted At',
                'Created At',
                'Updated At',
                'Witnesses Count',
                'Files Count'
            ]);

            foreach ($incidents as $incident) {
                // Format values for better readability
                $confidentialityDisplay = match ($incident->confidentiality) {
                    'low' => 'Public Report',
                    'medium' => 'Confidential Report',
                    'high' => 'Anonymous Report',
                    default => ucfirst($incident->confidentiality),
                };

                $statusDisplay = match ($incident->status) {
                    'processing' => 'Processing',
                    'resolved' => 'Resolved',
                    'dropped' => 'Dropped',
                    default => ucfirst($incident->status),
                };

                $reportTypeDisplay = match ($incident->report_type) {
                    'dispute' => 'Community Dispute',
                    'security' => 'Security Concern',
                    'public' => 'Public Safety',
                    'other' => 'Other Concern',
                    default => ucfirst($incident->report_type),
                };

                fputcsv($file, [
                    $incident->id,
                    $incident->reference_number,
                    $reportTypeDisplay,
                    $incident->incident_date ? Carbon::parse($incident->incident_date)->format('Y-m-d') : '',
                    $incident->incident_time,
                    $incident->location,
                    $incident->description,
                    $incident->immediate_action,
                    $incident->complainant_name,
                    $incident->complainant_contact,
                    $incident->complainant_address,
                    $incident->complainant_email,
                    $incident->respondent_name,
                    $incident->respondent_contact,
                    $incident->respondent_address,
                    $incident->respondent_description,
                    $confidentialityDisplay,
                    $incident->additional_info,
                    $statusDisplay,
                    $incident->deleted_at ? Carbon::parse($incident->deleted_at)->format('Y-m-d H:i:s') : '',
                    $incident->created_at ? Carbon::parse($incident->created_at)->format('Y-m-d H:i:s') : '',
                    $incident->updated_at ? Carbon::parse($incident->updated_at)->format('Y-m-d H:i:s') : '',
                    $incident->witnesses->count(),
                    $incident->files->count(),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}