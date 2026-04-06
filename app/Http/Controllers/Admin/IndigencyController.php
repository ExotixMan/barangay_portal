<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndigencyApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\StatusRemarkHistory;
use App\Mail\Notification as NotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndigencyController extends Controller
{
    public function index(Request $request)
    {
        $query = IndigencyApplication::query();

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
        // Filter by Income
        if ($request->monthly_income && in_array($request->monthly_income, ['below 5k', '5k-8k', '8k-10k', '10k-15k', 'no income'])) {
            $query->where('monthly_income', $request->monthly_income);
        }

        $total_count = IndigencyApplication::withTrashed()->count(); // Includes archived records
        $processing_count = IndigencyApplication::where('status', 'processing')->count();
        $approved_count   = IndigencyApplication::where('status', 'approved')->count();
        $rejected_count   = IndigencyApplication::where('status', 'rejected')->count();

        // SORTING
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['first_name', 'monthly_income', 'household_members','created_at'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if ($sort === 'monthly_income') {
            $query->orderByRaw("
                CASE monthly_income
                    WHEN '10k-15k' THEN 1
                    WHEN '8k-10k' THEN 2
                    WHEN '5k-8k' THEN 3
                    WHEN 'below 5k' THEN 4
                    WHEN 'no income' THEN 5
                END $direction
            ");
        } else {
            $query->orderBy($sort, $direction);
        }

        $indigency = $query->latest()->paginate(20);

        return view('admin.admin_indigency', compact('indigency', 'total_count', 'processing_count', 'approved_count', 'rejected_count'));
    }

    public function store(Request $request)
    {
        // Store form type in session
        session()->flash('form_type', 'add');

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
            'monthly_income' => 'required',
            'household_members' => ['required', 'regex:/^(?:[1-9]|1\\d|20)$/'],
            'purpose' => 'required|in:medical,scholarship,government,legal,employment,burial,other',
            'purpose_other' => 'required_if:purpose,other|nullable|string|max:255',
            'primary_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id_path' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            // Proof of residency
            if ($request->hasFile('primary_proof')) {
                $proof = $request->file('primary_proof');
                $proofExt = $proof->getClientOriginalExtension();
                $proofName = time() . '_proof_' . Str::random(5) . '.' . $proofExt;
                $proof->move(public_path('uploads/proof_of_residency/indigency'), $proofName);
                $data['primary_proof'] = 'uploads/proof_of_residency/indigency/' . $proofName;
            }

            //Valid ID
            if ($request->hasFile('valid_id_path')){
                $vip = $request->file('valid_id_path');
                $vip_ext = $vip->getClientOriginalExtension();
                $vip_name = time() . '.' . $vip_ext;
                $vip->move(public_path('uploads/valid_id/indigency'), $vip_name);
                $data['valid_id_path'] = 'uploads/valid_id/indigency/' . $vip_name;
            }

            // Generate reference number
            $data['reference_number'] = 'IND-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

            $data['status'] = 'processing';

            IndigencyApplication::create($data);

            return redirect()->route('admin.indigency.index') // FIXED: added redirect with proper route
                ->with('success', 'Indigency added successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to submit application. Please try again. ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $application = IndigencyApplication::findOrFail($id);

        // Store form type in session with application ID
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
            'birthdate' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'contact_number' => ['required', 'regex:/^09\d{9}$/'],
            'email' => 'required|email|max:255',
            'monthly_income' => 'required|in:below 5k,5k-8k,8k-10k,10k-15k,no income',
            'household_members' => ['required', 'regex:/^(?:[1-9]|1\\d|20)$/'],
            'purpose' => 'required|in:medical,scholarship,government,legal,employment,burial,other',
            'purpose_other' => 'required_if:purpose,other|nullable|string|max:255',
            'primary_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            if ($request->hasFile('primary_proof')) {
                if ($application->primary_proof && file_exists(public_path($application->primary_proof))) {
                    unlink(public_path($application->primary_proof));
                }

                $proof = $request->file('primary_proof');
                $proofExt = $proof->getClientOriginalExtension();
                $proofName = time() . '_proof_' . Str::random(5) . '.' . $proofExt;
                $proof->move(public_path('uploads/proof_of_residency/indigency'), $proofName);
                $data['primary_proof'] = 'uploads/proof_of_residency/indigency/' . $proofName;
            }

            if ($request->hasFile('valid_id_path')) {
                if ($application->valid_id_path && file_exists(public_path($application->valid_id_path))) {
                    unlink(public_path($application->valid_id_path));
                }

                $vip = $request->file('valid_id_path');
                $vipExt = $vip->getClientOriginalExtension();
                $vipName = time() . '_id_' . Str::random(5) . '.' . $vipExt;
                $vip->move(public_path('uploads/valid_id/indigency'), $vipName);
                $data['valid_id_path'] = 'uploads/valid_id/indigency/' . $vipName;
            }

            $application->update($data);

            return back()->with('success', 'Indigency application updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update application. Please try again. ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $application = IndigencyApplication::findOrFail($id);
        
        // Validate status
        $allowedStatuses = ['pending', 'under_review', 'processing', 'approved', 'ready_pickup', 'claimed', 'rejected'];
        
        $request->validate([
            'status' => 'required|in:' . implode(',', $allowedStatuses),
            'remarks' => 'nullable|required_if:status,rejected|string|max:40',
        ]);

        $newStatus = $request->status;
        $oldStatus = $application->status;
        $remarks = trim((string) $request->input('remarks', ''));

        // Optional: Add business logic rules for status transitions
        // For example, prevent certain transitions if needed
        
        // Update status and timestamp
        $application->status = $newStatus;
        $application->status_updated_at = now();
        $application->save();

        StatusRemarkHistory::create([
            'request_type' => 'indigency',
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
                'module' => 'Indigency',
                'details' => [
                    'application_id' => $application->id,
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

        $deliverySummary = $this->formatDeliverySummary($deliveryResults);

        return back()->with('success', "Application #{$application->reference_number} status updated from " . 
            ucfirst(str_replace('_', ' ', $oldStatus)) . " to {$statusDisplay}. Notifications: {$deliverySummary}.");
    }

    private function sendStatusNotifications(Request $request, IndigencyApplication $application, string $status, string $remarks): array
    {
        $results = ['email' => 'not selected', 'sms' => 'not selected'];
        $allowed = ['approved', 'ready_pickup', 'rejected'];
        if (!in_array($status, $allowed, true)) {
            return ['email' => 'not available for status', 'sms' => 'not available for status'];
        }

        $statusLabel = ucfirst(str_replace('_', ' ', $status));
        $remarksText = ($status === 'rejected' && $remarks !== '') ? " Remarks: {$remarks}" : '';

        if ($request->boolean('notify_email')) {
            if (empty($application->email)) {
                $results['email'] = 'failed (missing email)';
            } else {
            try {
                $message = "Your barangay indigency application (Ref: {$application->reference_number}) status is now {$statusLabel}.{$remarksText}";
                Mail::to($application->email)->send(new NotificationMail(trim($application->first_name . ' ' . $application->last_name), $message));

                $this->storeNotificationHistory(
                    'indigency',
                    (int) $application->id,
                    (string) $application->reference_number,
                    $status,
                    $remarks !== '' ? $remarks : 'Email status update sent.',
                    'email',
                    $application->email
                );
                $results['email'] = 'sent';
            } catch (\Throwable $e) {
                Log::warning('Failed sending indigency status email.', ['error' => $e->getMessage(), 'id' => $application->id]);
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

                $smsMessage = "Indigency {$application->reference_number}: status {$statusLabel}.{$remarksText}";
                $response = Http::asForm()->post('https://semaphore.co/api/v4/messages', [
                    'apikey' => env('SMS_API_KEY'),
                    'number' => $recipient,
                    'message' => $smsMessage,
                    'sendername' => env('SMS_SENDER_NAME'),
                ]);

                if ($response->successful()) {
                    $this->storeNotificationHistory(
                        'indigency',
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
                Log::warning('Failed sending indigency status SMS.', ['error' => $e->getMessage(), 'id' => $application->id]);
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
            Log::warning('Failed storing indigency notification history.', ['error' => $e->getMessage(), 'id' => $requestId]);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:indigency_applications,id'
        ]);

        IndigencyApplication::whereIn('id', $request->ids)->delete();

        // Log audit trail
        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'BULK_ARCHIVE',
                'module' => 'Indigency',
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
            'ids.*' => 'exists:indigency_applications,id'
        ]);

        $query = IndigencyApplication::query();

        if ($request->ids) {
            $query->whereIn('id', $request->ids);
        }

        $applications = $query->get();

        $filename = 'indigency_applications_' . now()->format('Y-m-d_His') . '.csv'; // FIXED: added timestamp

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
                'Monthly Income',
                'Household Members',
                'Purpose',
                'Purpose Other',
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

                $incomeDisplay = match ($app->monthly_income) {
                    'below 5k' => 'Below ₱5,000',
                    '5k-8k' => '₱5,000 – ₱8,000',
                    '8k-10k' => '₱8,001 – ₱10,000',
                    '10k-15k' => '₱10,001 – ₱15,000',
                    'no income' => 'No fixed income',
                    default => $app->monthly_income,
                };

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
                    $incomeDisplay,
                    $app->household_members,
                    $app->purpose,
                    $app->purpose_other,
                    $app->status,
                    $app->created_at,
                    $app->updated_at,
                ]);
            }

            fclose($file);
        };

        // Log audit trail
        if (auth('admin')->check()) {
            $exportCount = IndigencyApplication::query()->when($request->ids, function($q) use ($request) {
                return $q->whereIn('id', $request->ids);
            })->count();
            
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'EXPORT',
                'module' => 'Indigency',
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
        $application = IndigencyApplication::findOrFail($id);

        $reference = $application->reference_number;
        $application->delete();

        if (auth('admin')->check()) {
            $request = request();
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'APPLICATION_ARCHIVED',
                'module' => 'Indigency',
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
        $application = IndigencyApplication::withTrashed()->findOrFail($id);
        $reference = $application->reference_number;
        $application->restore();

        if (auth('admin')->check()) {
            $request = request();
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'APPLICATION_RESTORED',
                'module' => 'Indigency',
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

        $record = IndigencyApplication::findOrFail($id);

        $templatePath = public_path('document_template/Doc2.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Word template not found');
        }

        $full_name = trim($record->first_name . ' ' . $record->middle_name . ' ' . $record->last_name . ' ' . $record->suffix);

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('SERVICE_TYPE', 'Certification of Indigency');
        $templateProcessor->setValue('FULL_NAME', $full_name);
        $templateProcessor->setValue('DATE_ISSUED', Carbon::parse($record->created_at)->format('F d, Y')); // FIXED: changed created_ad to created_at

        $fileName = 'certification_of_indigency_' . $record->reference_number . '.docx';
        $outputDir = storage_path('app/generated');
        if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);

        $docxPath = $outputDir . '/' . $fileName;
        $templateProcessor->saveAs($docxPath);

        // Log audit trail
        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'DOWNLOAD',
                'module' => 'Indigency',
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

            return $pdf->stream('certification_of_indigency.pdf');
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }
}