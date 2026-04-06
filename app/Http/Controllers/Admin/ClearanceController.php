<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangayClearance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
use App\Models\StatusRemarkHistory;
use App\Mail\Notification as NotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClearanceController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangayClearance::query();

        $search = preg_replace('/[^a-zA-Z0-9\s\-@.]/', '', $request->search ?? ''); // FIXED: added null coalescing

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->status) {
            if ($request->status === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $request->status);
            }
        }
        // Filter by Purpose
        if ($request->purpose && in_array($request->purpose, ['employment', 'business', 'scholarship', 'travel', 'bank', 'government', 'school', 'other'])) {
            $query->where('purpose', $request->purpose);
        }

        $total_count = BarangayClearance::withTrashed()->count(); // Includes archived records
        $processing_count = BarangayClearance::where('status', 'processing')->count();
        $approved_count   = BarangayClearance::where('status', 'approved')->count();
        $rejected_count   = BarangayClearance::where('status', 'rejected')->count();

        // SORTING
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['first_name', 'created_at'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $query->orderBy($sort, $direction);

        $clearances = $query->latest()->paginate(20);
        
        return view('admin.admin_clearance', compact('clearances', 'total_count', 'processing_count', 'approved_count', 'rejected_count'));
    }

    public function store(Request $request)
    {
        // Normalize phone input before validation.
        $request->merge([
            'contact_number' => preg_replace('/\D+/', '', (string) $request->input('contact_number')),
        ]);

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'address' => 'required',
            'contact_number' => ['required','regex:/^09\d{9}$/'],
            'email' => 'required|email',
            'primary_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id_path' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'purpose' => 'required',
            'purpose_other' => 'required_if:purpose,other|nullable|string|max:255',
            'fee' => ['nullable', 'regex:/^(?:0|[1-9]\d{0,4})(?:\.\d{1,2})?$/'],
        ]);

        try {
            // Proof of residency
            if ($request->hasFile('primary_proof')) {
                $proof = $request->file('primary_proof');
                $proofExt = $proof->getClientOriginalExtension();
                $proofName = time() . '_proof_' . Str::random(5) . '.' . $proofExt;
                $proof->move(public_path('uploads/proof_of_residency/clearance'), $proofName);
                $data['primary_proof'] = 'uploads/proof_of_residency/clearance/' . $proofName;
            }

            //Valid ID
            if ($request->hasFile('valid_id_path')){
                $vip = $request->file('valid_id_path');
                $vip_ext = $vip->getClientOriginalExtension();
                $vip_name = time() . '.' . $vip_ext;
                $vip->move(public_path('uploads/valid_id/clearance'), $vip_name);
                $data['valid_id_path'] = 'uploads/valid_id/clearance/' . $vip_name;
            }

            // Generate Reference Number
            $data['reference_number'] = 'BC-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

            if (array_key_exists('fee', $data) && $data['fee'] !== null && $data['fee'] !== '') {
                $data['fee'] = round((float) $data['fee'], 2);
            } else {
                $data['fee'] = null;
            }

            // Default status
            $data['status'] = 'processing';

            BarangayClearance::create($data);

            return redirect()->route('admin.clearance.index') // FIXED: added redirect with proper route
                ->with('success', 'Clearance added successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to submit application. Please try again. ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $application = BarangayClearance::findOrFail($id);

        session()->flash('form_type', 'edit_' . $application->id);

        // Normalize phone input before validation.
        $request->merge([
            'contact_number' => preg_replace('/\D+/', '', (string) $request->input('contact_number')),
        ]);

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birthdate' => 'required|date|before_or_equal:today',
            'gender' => 'required',
            'address' => 'required|string',
            'contact_number' => ['required', 'regex:/^09\d{9}$/'],
            'email' => 'required|email',
            'primary_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'purpose' => 'required',
            'purpose_other' => 'required_if:purpose,other|nullable|string|max:255',
            'fee' => ['nullable', 'regex:/^(?:0|[1-9]\d{0,4})(?:\.\d{1,2})?$/'],
        ]);

        try {
            if ($request->hasFile('primary_proof')) {
                if ($application->primary_proof && File::exists(public_path($application->primary_proof))) {
                    File::delete(public_path($application->primary_proof));
                }

                $proof = $request->file('primary_proof');
                $proofExt = $proof->getClientOriginalExtension();
                $proofName = time() . '_proof_' . Str::random(5) . '.' . $proofExt;
                $proof->move(public_path('uploads/proof_of_residency/clearance'), $proofName);
                $data['primary_proof'] = 'uploads/proof_of_residency/clearance/' . $proofName;
            }

            if ($request->hasFile('valid_id_path')) {
                if ($application->valid_id_path && File::exists(public_path($application->valid_id_path))) {
                    File::delete(public_path($application->valid_id_path));
                }

                $vip = $request->file('valid_id_path');
                $vipExt = $vip->getClientOriginalExtension();
                $vipName = time() . '_' . Str::random(5) . '.' . $vipExt;
                $vip->move(public_path('uploads/valid_id/clearance'), $vipName);
                $data['valid_id_path'] = 'uploads/valid_id/clearance/' . $vipName;
            }

            if (array_key_exists('fee', $data) && $data['fee'] !== null && $data['fee'] !== '') {
                $data['fee'] = round((float) $data['fee'], 2);
            } else {
                $data['fee'] = null;
            }

            $application->update($data);

            return redirect()->route('admin.clearance.index')
                ->with('success', 'Application #' . $application->reference_number . ' updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update application. Please try again. ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $application = BarangayClearance::findOrFail($id);
        
        // Validate status
        $allowedStatuses = ['pending', 'under_review', 'processing', 'approved', 'ready_pickup', 'claimed', 'rejected'];
        
        $request->validate([
            'status' => 'required|in:' . implode(',', $allowedStatuses),
            'remarks' => 'nullable|required_if:status,rejected|string|max:40',
            'fee' => ['nullable', 'regex:/^(?:0|[1-9]\d{0,5})(?:\.\d{1,2})?$/'],
        ]);

        $newStatus = $request->status;
        $oldStatus = $application->status;
        $remarks = trim((string) $request->input('remarks', ''));
        $feeValue = $request->filled('fee') ? (float) $request->input('fee') : null;

        // Optional: Add business logic rules for status transitions
        // For example, prevent certain transitions if needed
        
        // Update status and timestamp
        $application->status = $newStatus;
        $application->fee = $feeValue;
        $application->status_updated_at = now();
        $application->save();

        StatusRemarkHistory::create([
            'request_type' => 'clearance',
            'request_id' => $application->id,
            'reference_number' => $application->reference_number,
            'status' => $newStatus,
            'remarks' => $remarks !== '' ? $remarks : 'Status updated by admin.',
            'channel' => 'system',
            'recipient' => null,
            'admin_user_id' => auth('admin')->id(),
        ]);

        $deliveryResults = $this->sendStatusNotifications($request, $application, $newStatus, $remarks);

        // Log approval/status update
        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'APPROVAL_STATUS_CHANGE',
                'module' => 'Clearance',
                'details' => [
                    'clearance_id' => $application->id,
                    'reference_number' => $application->reference_number,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'approved_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        // Format status name for display
        $statusDisplay = ucfirst(str_replace('_', ' ', $newStatus));

        $feeText = is_null($application->fee)
            ? 'Depending on purpose'
            : 'PHP ' . number_format((float) $application->fee, 2);

        $deliverySummary = $this->formatDeliverySummary($deliveryResults);

        return back()->with('success', "Application #{$application->reference_number} status updated from " . 
            ucfirst(str_replace('_', ' ', $oldStatus)) . " to {$statusDisplay}. Current fee: {$feeText}. Notifications: {$deliverySummary}.");
    }

    private function sendStatusNotifications(Request $request, BarangayClearance $application, string $status, string $remarks): array
    {
        $results = ['email' => 'not selected', 'sms' => 'not selected'];
        $allowed = ['approved', 'ready_pickup', 'rejected'];
        if (!in_array($status, $allowed, true)) {
            return ['email' => 'not available for status', 'sms' => 'not available for status'];
        }

        $statusLabel = ucfirst(str_replace('_', ' ', $status));
        $feeText = is_null($application->fee) ? 'Depending on purpose' : 'PHP ' . number_format((float) $application->fee, 2);
        $remarksText = ($status === 'rejected' && $remarks !== '') ? " Remarks: {$remarks}" : '';

        if ($request->boolean('notify_email')) {
            if (empty($application->email)) {
                $results['email'] = 'failed (missing email)';
            } else {
            try {
                $message = "Your barangay clearance application (Ref: {$application->reference_number}) status is now {$statusLabel}. Fee: {$feeText}.{$remarksText}";
                Mail::to($application->email)->send(new NotificationMail(trim($application->first_name . ' ' . $application->last_name), $message));

                $this->storeNotificationHistory(
                    'clearance',
                    (int) $application->id,
                    (string) $application->reference_number,
                    $status,
                    $remarks !== '' ? $remarks : 'Email status update sent.',
                    'email',
                    $application->email
                );
                $results['email'] = 'sent';
            } catch (\Throwable $e) {
                Log::warning('Failed sending clearance status email.', ['error' => $e->getMessage(), 'id' => $application->id]);
                $results['email'] = 'failed';
            }
            }
        }

        if ($request->boolean('notify_sms')) {
            if (empty($application->contact_number)) {
                $results['sms'] = 'failed (missing mobile number)';
            } else {
            try {
                $recipient = preg_replace('/\D/', '', (string) $application->contact_number);
                if (substr($recipient, 0, 1) === '0') {
                    $recipient = '63' . substr($recipient, 1);
                } elseif (substr($recipient, 0, 1) === '9') {
                    $recipient = '63' . $recipient;
                }

                $smsMessage = "Clearance {$application->reference_number}: status {$statusLabel}. Fee {$feeText}.{$remarksText}";
                $response = Http::asForm()->post('https://semaphore.co/api/v4/messages', [
                    'apikey' => env('SMS_API_KEY'),
                    'number' => $recipient,
                    'message' => $smsMessage,
                    'sendername' => env('SMS_SENDER_NAME'),
                ]);

                if ($response->successful()) {
                    $this->storeNotificationHistory(
                        'clearance',
                        (int) $application->id,
                        (string) $application->reference_number,
                        $status,
                        $remarks !== '' ? $remarks : 'SMS status update sent.',
                        'sms',
                        $recipient
                    );
                    $results['sms'] = 'sent';
                } else {
                    $results['sms'] = 'failed';
                }
            } catch (\Throwable $e) {
                Log::warning('Failed sending clearance status SMS.', ['error' => $e->getMessage(), 'id' => $application->id]);
                $results['sms'] = 'failed';
            }
            }
        }

        return $results;
    }

    private function formatDeliverySummary(array $results): string
    {
        return 'Email ' . ($results['email'] ?? 'not selected') . ', SMS ' . ($results['sms'] ?? 'not selected');
    }

    private function storeNotificationHistory(string $requestType, int $requestId, string $reference, string $status, string $remarks, string $channel, ?string $recipient): void
    {
        try {
            StatusRemarkHistory::create([
                'request_type' => $requestType,
                'request_id' => $requestId,
                'reference_number' => $reference,
                'status' => $status,
                'remarks' => $remarks,
                'channel' => $channel,
                'recipient' => $recipient,
                'admin_user_id' => auth('admin')->id(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed storing clearance notification history.', ['error' => $e->getMessage(), 'id' => $requestId]);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([    
            'ids' => 'required|array',
            'ids.*' => 'exists:barangay_clearances,id'
        ]);
        
        BarangayClearance::whereIn('id', $request->ids)->delete();

        // Log audit trail
        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'BULK_ARCHIVE',
                'module' => 'Clearance',
                'details' => [
                    'ids' => $request->ids,
                    'count' => count($request->ids),
                    'archived_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return back()->with('success', count($request->ids) . ' selected application(s) archived successfully.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'exists:barangay_clearances,id'
        ]);

        $query = BarangayClearance::query();

        if ($request->ids) {
            $query->whereIn('id', $request->ids);
        }

        $applications = $query->get();

        $filename = 'barangay_clearances_' . now()->format('Y-m-d_His') . '.csv'; // FIXED: added timestamp

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($applications) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            // CSV Header Row
            fputcsv($file, [
                'ID',
                'Reference Number',
                'Full Name',
                'First Name',
                'Middle Name',
                'Last Name',
                'Suffix',
                'Birthdate',
                'Gender',
                'Address',
                'Contact Number',
                'Email',
                'Purpose',
                'Purpose Other',
                'Fee',
                'Status',
                'Created At',
                'Updated At'
            ]);

            foreach ($applications as $app) {

                $full_name = trim(
                    $app->first_name . ' ' .
                    $app->middle_name . ' ' .
                    $app->last_name . ' ' .
                    $app->suffix
                );

                fputcsv($file, [
                    $app->id,
                    $app->reference_number,
                    $full_name,
                    $app->first_name,
                    $app->middle_name,
                    $app->last_name,
                    $app->suffix,
                    $app->birthdate,
                    $app->gender,
                    $app->address,
                    $app->contact_number,
                    $app->email,
                    $app->purpose,
                    $app->purpose_other,
                    is_null($app->fee) ? 'Depending on purpose' : number_format((float) $app->fee, 2, '.', ''),
                    $app->status,
                    $app->created_at,
                    $app->updated_at,
                ]);
            }

            fclose($file);
        };

        // Log audit trail
        if (auth('admin')->check()) {
            $exportCount = BarangayClearance::query()->when($request->ids, function($q) use ($request) {
                return $q->whereIn('id', $request->ids);
            })->count();
            
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'EXPORT',
                'module' => 'Clearance',
                'details' => [
                    'ids' => $request->ids ?: 'all',
                    'count' => $exportCount,
                    'exported_by' => auth('admin')->user()?->full_name ?? 'Admin',
                    'file_format' => 'CSV'
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        $application = BarangayClearance::findOrFail($id);

        $reference = $application->reference_number;
        $application->delete();

        if (auth('admin')->check()) {
            $request = request();
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'APPLICATION_ARCHIVED',
                'module' => 'Clearance',
                'details' => [
                    'application_id' => $application->id,
                    'reference_number' => $reference,
                    'archived_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
        
        return back()->with('success', 'Application #' . $reference . ' archived successfully.');
    }

    public function restore($id)
    {
        $application = BarangayClearance::withTrashed()->findOrFail($id);
        $reference = $application->reference_number;
        $application->restore();

        if (auth('admin')->check()) {
            $request = request();
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'APPLICATION_RESTORED',
                'module' => 'Clearance',
                'details' => [
                    'application_id' => $application->id,
                    'reference_number' => $reference,
                    'restored_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return back()->with('success', 'Application #' . $reference . ' restored successfully.');
    }

    public function generate(Request $request)
    {
        $id = $request->query('id');
        $action = $request->query('action', 'download');

        $record = BarangayClearance::findOrFail($id);

        $clearanceTemplatePath = public_path('document_template/Clearance_Template.docx');
        $templatePath = file_exists($clearanceTemplatePath)
            ? $clearanceTemplatePath
            : public_path('document_template/Doc2.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Word template not found');
        }

        $nameParts = array_filter([
            $record->first_name,
            $record->middle_name,
            $record->last_name,
            $record->suffix,
        ]);
        $full_name = strtoupper(trim(implode(' ', $nameParts)));

        $createdDate = Carbon::parse($record->created_at);
        $day = strtoupper($createdDate->format('jS'));
        $month = strtoupper($createdDate->format('F'));
        $year = strtoupper($createdDate->format('Y'));

        $address = strtoupper($record->address ?? 'N/A');
        $purpose = $record->purpose === 'other'
            ? strtoupper($record->purpose_other ?? 'N/A')
            : strtoupper(str_replace('_', ' ', $record->purpose ?? 'N/A'));

        $adminUser = auth('admin')->user();
        $resolvedStaffName = $adminUser?->full_name
            ?? trim(($adminUser?->first_name ?? '') . ' ' . ($adminUser?->last_name ?? ''))
            ?? $adminUser?->username
            ?? 'BARANGAY STAFF';
        $staffName = strtoupper(trim($resolvedStaffName));

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('SERVICE_TYPE', 'Barangay Clearance');
        $templateProcessor->setValue('FULL_NAME', $full_name);
        $templateProcessor->setValue('ADDRESS', $address);
        $templateProcessor->setValue('PURPOSE', $purpose);
        $templateProcessor->setValue('DAY', $day);
        $templateProcessor->setValue('MONTH', $month);
        $templateProcessor->setValue('YEAR', $year);
        $templateProcessor->setValue('STAFF_NAME', $staffName);
        $templateProcessor->setValue('DATE_ISSUED', Carbon::parse($record->created_at)->format('F d, Y')); // FIXED: changed created_ad to created_at

        $fileName = 'barangay_clearance_' . $record->reference_number . '.docx'; // FIXED: added underscore
        $outputDir = storage_path('app/generated');
        if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);

        $docxPath = $outputDir . '/' . $fileName;
        $templateProcessor->saveAs($docxPath);

        // Log audit trail
        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'DOWNLOAD',
                'module' => 'Clearance',
                'details' => [
                    'record_id' => $record->id,
                    'reference_number' => $record->reference_number,
                    'document_name' => $record->reference_number,
                    'file_type' => 'Word Document',
                    'action_type' => $action,
                    'downloaded_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        if ($action === 'print') {
            $phpWord = IOFactory::load($docxPath);
            $objWriter = IOFactory::createWriter($phpWord, 'HTML');

            $tempHtml = $outputDir . '/temp.html';
            $objWriter->save($tempHtml);

            $htmlContent = file_get_contents($tempHtml);
            $pdf = Pdf::loadHTML($htmlContent);

            return $pdf->stream('barangay_clearance.pdf');
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }
}