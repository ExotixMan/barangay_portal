<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DocumentTemplateStorageService;
use App\Support\HandlesPrivateUploads;
use App\Models\IndigencyApplication;
use App\Models\Residents;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use App\Models\StatusRemarkHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AdminActivityLog;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; 

class IndigencyController extends Controller
{
    use HandlesPrivateUploads;
    public function index(Request $request)
    {
        $query = IndigencyApplication::query();

        $search = preg_replace('/[^a-zA-Z0-9\s\-@.]/', '', $request->search ?? ''); 

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->status) {
            if ($request->status === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $request->status);
            }
        }
        
        if ($request->monthly_income && in_array($request->monthly_income, ['below 5k', '5k-8k', '8k-10k', '10k-15k', 'no income'])) {
            $query->where('monthly_income', $request->monthly_income);
        }

        $total_count = IndigencyApplication::withTrashed()->count(); 
        $processing_count = IndigencyApplication::where('status', 'processing')->count();
        $approved_count   = IndigencyApplication::where('status', 'approved')->count();
        $rejected_count   = IndigencyApplication::where('status', 'rejected')->count();

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['first_name', 'monthly_income', 'household_members','created_at'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $query->orderByRaw("CASE WHEN status = 'rejected' THEN 1 ELSE 0 END ASC");

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

        $indigency = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $residents = Residents::query()
            ->whereNull('deleted_at')
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get([
                'id', 'firstname', 'middlename', 'lastname', 'suffix', 'birthdate',
                'gender', 'civil_status', 'place_of_birth', 'address', 'contact', 'email',
                'valid_id', 'valid_id_back', 'valid_id_status', 'valid_id_verified',
            ]);

        return view('admin.admin_indigency', compact('indigency', 'residents', 'total_count', 'processing_count', 'approved_count', 'rejected_count'));
    }

    public function store(Request $request)
    {
        
        session()->flash('form_type', 'add');

        $request->merge([
            'contact_number' => preg_replace('/\D+/', '', (string) $request->input('contact_number')),
        ]);

        $selectedResident = null;
        if ($request->filled('resident_id')) {
            $selectedResident = Residents::whereKey($request->input('resident_id'))
                ->whereNull('deleted_at')
                ->firstOrFail();

            $request->merge([
                'first_name' => $selectedResident->firstname,
                'middle_name' => $selectedResident->middlename,
                'last_name' => $selectedResident->lastname,
                'suffix' => $selectedResident->suffix,
                'birthdate' => optional($selectedResident->birthdate)->format('Y-m-d'),
                'gender' => strtolower((string) $selectedResident->gender),
                'civil_status' => strtolower((string) $selectedResident->civil_status),
                'birth_place' => $selectedResident->place_of_birth,
                'address' => $selectedResident->address,
                'contact_number' => $selectedResident->contact,
                'email' => $selectedResident->email,
            ]);
        }

        $data = $request->validate([
            'resident_id' => [
                'nullable',
                'integer',
                Rule::exists('residents', 'id')
                    ->whereNull('deleted_at'),
            ],

            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female,other,prefer not to say',
            'civil_status' => 'required|in:single,married,widowed,separated,divorced',
            'birth_place' => 'required|string|max:255',
            'address' => 'required',
            'contact_number' => ['required', 'regex:/^09\d{9}$/'],
            'email' => 'required|email',

            'monthly_income' => 'required|in:below 5k,5k-8k,8k-10k,10k-15k,no income',

            'household_members' => [
                'required',
                'regex:/^(?:[1-9]|1\d|20)$/'
            ],

            'purpose' => [
                'required',
                'in:medical,scholarship,government,legal,employment,burial,other'
            ],

            'purpose_other' => [
                'required_if:purpose,other',
                'nullable',
                'string',
                'max:255'
            ],

            'primary_proof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],

            'valid_id_path' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],

            'valid_id_back_path' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],
        ]);

        if ($selectedResident) {
            $data['user_id'] = $selectedResident->id;
            if (! $request->hasFile('valid_id_path') && filled($selectedResident->valid_id)) {
                $data['valid_id_path'] = $selectedResident->valid_id;
            }
            if (! $request->hasFile('valid_id_back_path') && filled($selectedResident->valid_id_back)) {
                $data['valid_id_back_path'] = $selectedResident->valid_id_back;
            }
        }

        unset($data['resident_id']);

        $documentErrors = [];
        if (! filled($data['valid_id_path'] ?? null)) {
            $documentErrors['valid_id_path'] = 'Select a resident with a saved front Valid ID or upload the front side.';
        }
        if (! filled($data['valid_id_back_path'] ?? null)) {
            $documentErrors['valid_id_back_path'] = 'Select a resident with a saved back Valid ID or upload the back side.';
        }
        if ($documentErrors) {
            return back()->withInput()->withErrors($documentErrors);
        }

        try {
            
            if ($request->hasFile('primary_proof')) {
                $proof = $request->file('primary_proof');
                $proofExt = $proof->getClientOriginalExtension();
                $proofName = time() . '_proof_' . Str::random(5) . '.' . $proofExt;
                $data['primary_proof'] = $this->storePrivateUpload($proof, 'proof_of_residency/indigency', 'proof');
            }

            if ($request->hasFile('valid_id_path')){
                $vip = $request->file('valid_id_path');
                $vip_ext = $vip->getClientOriginalExtension();
                $vip_name = time() . '_id_front_' . Str::random(5) . '.' . $vip_ext;
                $data['valid_id_path'] = $this->storePrivateUpload($vip, 'valid_id/indigency', 'id_front');
            }

            if ($request->hasFile('valid_id_back_path')){
                $vipBack = $request->file('valid_id_back_path');
                $vipBackExt = $vipBack->getClientOriginalExtension();
                $vipBackName = time() . '_id_back_' . Str::random(5) . '.' . $vipBackExt;
                $data['valid_id_back_path'] = $this->storePrivateUpload($vipBack, 'valid_id/indigency', 'id_back');
            }

            $data['reference_number'] = 'IND-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

            $data['status'] = 'processing';

            IndigencyApplication::create($data);

            return redirect()->route('admin.indigency.index') 
                ->with('success', 'Indigency added successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to submit application. Please try again.');
        }
    }

    public function update(Request $request, int $id)
    {
        $application = IndigencyApplication::findOrFail($id);

        session()->flash('form_type', 'edit_' . $application->id);

        $request->merge([
            'contact_number' => preg_replace('/\D+/', '', (string) $request->input('contact_number')),
        ]);

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birthdate' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other,prefer not to say',
            'civil_status' => 'required|in:single,married,widowed,separated,divorced',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_number' => ['required', 'regex:/^09\d{9}$/'],
            'email' => 'required|email|max:255',
            'monthly_income' => 'required|in:below 5k,5k-8k,8k-10k,10k-15k,no income',
            'household_members' => ['required', 'regex:/^(?:[1-9]|1\\d|20)$/'],
            'purpose' => 'required|in:medical,scholarship,government,legal,employment,burial,other',
            'purpose_other' => 'required_if:purpose,other|nullable|string|max:255',
            'primary_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id_back_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $oldSensitiveFiles = [
            'primary_proof' => $application->primary_proof,
            'valid_id_path' => $application->valid_id_path,
            'valid_id_back_path' => $application->valid_id_back_path,
        ];

        try {
            if ($request->hasFile('primary_proof')) {
$proof = $request->file('primary_proof');
                $proofExt = $proof->getClientOriginalExtension();
                $proofName = time() . '_proof_' . Str::random(5) . '.' . $proofExt;
                $data['primary_proof'] = $this->storePrivateUpload($proof, 'proof_of_residency/indigency', 'proof');
            }

            if ($request->hasFile('valid_id_path')) {
$vip = $request->file('valid_id_path');
                $vipExt = $vip->getClientOriginalExtension();
                $vipName = time() . '_id_front_' . Str::random(5) . '.' . $vipExt;
                $data['valid_id_path'] = $this->storePrivateUpload($vip, 'valid_id/indigency', 'id_front');
            }

            if ($request->hasFile('valid_id_back_path')) {
$vipBack = $request->file('valid_id_back_path');
                $vipBackExt = $vipBack->getClientOriginalExtension();
                $vipBackName = time() . '_id_back_' . Str::random(5) . '.' . $vipBackExt;
                $data['valid_id_back_path'] = $this->storePrivateUpload($vipBack, 'valid_id/indigency', 'id_back');
            }

            $application->update($data);

            foreach ($oldSensitiveFiles as $field => $oldPath) {
                if ($request->hasFile($field) && $oldPath && $oldPath !== ($application->{$field} ?? null)) {
                    $this->deleteStoredUpload($oldPath);
                }
            }

            return back()->with('success', 'Indigency application updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update application. Please try again.');
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        $application = IndigencyApplication::findOrFail($id);

        $allowedStatuses = ['pending', 'under_review', 'processing', 'approved', 'ready_pickup', 'claimed', 'rejected'];
        
        $request->validate([
            'status' => 'required|in:' . implode(',', $allowedStatuses),
            'remarks' => 'nullable|required_if:status,rejected|string|max:40',
        ]);

        $newStatus = $request->status;
        $oldStatus = $application->status;
        $remarks = trim((string) $request->input('remarks', ''));

        

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
                app(\App\Services\EmailDeliveryService::class)->sendNotification($application->email, trim($application->first_name . ' ' . $application->last_name), $message);

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
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('indigency_applications', 'id')
                    ->whereNull('deleted_at'),
            ],
        ]);

        $ids = array_values(array_unique($validated['ids']));

        $archivedCount = DB::transaction(function () use ($ids, $request) {
            $applications = IndigencyApplication::query()
                ->whereIn('id', $ids)
                ->get();

            foreach ($applications as $application) {
                
                $application->delete();
            }

            if (auth('admin')->check()) {
                AdminActivityLog::create([
                    'user_id' => auth('admin')->id(),
                    'action' => 'BULK_ARCHIVE',
                    'module' => 'Indigency',
                    'details' => [
                        'ids' => $applications->pluck('id')->all(),
                        'count' => $applications->count(),
                        'status' => 'archived',
                        'archived_by' => auth('admin')->user()?->full_name ?? 'Admin',
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            return $applications->count();
        });

        return back()->with(
            'success',
            $archivedCount . ' selected application(s) archived successfully.'
        );
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

        $filename = 'indigency_applications_' . now()->format('Y-m-d_His') . '.csv'; 

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($applications) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

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

    public function destroy(int $id)
    {
        $application = IndigencyApplication::findOrFail($id);

        $reference = $application->reference_number;

        DB::transaction(function () use ($application, $reference) {

            $application->delete();

            if (auth('admin')->check()) {
                $request = request();

                AdminActivityLog::create([
                    'user_id' => auth('admin')->id(),
                    'action' => 'APPLICATION_ARCHIVED',
                    'module' => 'Indigency',
                    'details' => [
                        'application_id' => $application->id,
                        'reference_number' => $reference,
                        'previous_status' => $application->getOriginal('status'),
                        'new_status' => 'archived',
                        'archived_by' => auth('admin')->user()?->full_name ?? 'Admin',
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        });

        return back()->with(
            'success',
            'Application #' . $reference . ' archived successfully.'
        );
    }

    public function restore(int $id)
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

    public function bulkRestore(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:indigency_applications,id'
        ]);

        IndigencyApplication::withTrashed()
            ->whereIn('id', $request->ids)
            ->restore();

        return back()->with('success', 'Selected records restored successfully.');
    }

    public function generate(Request $request)
    {
        $id = $request->query('id');
        $action = $request->query('action', 'download');

        $record = IndigencyApplication::findOrFail($id);

        $templatePath = app(DocumentTemplateStorageService::class)->path('Doc2.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Word template not found');
        }

        $full_name = trim($record->first_name . ' ' . $record->middle_name . ' ' . $record->last_name . ' ' . $record->suffix);

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('SERVICE_TYPE', 'Certification of Indigency');
        $templateProcessor->setValue('FULL_NAME', $full_name);
        $templateProcessor->setValue('DATE_ISSUED', Carbon::parse($record->created_at)->format('F d, Y')); 

        $fileName = 'certification_of_indigency_' . $record->reference_number . '.docx';
        $outputDir = storage_path('app/generated');
        if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);

        $docxPath = $outputDir . '/' . $fileName;
        $templateProcessor->saveAs($docxPath);

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
            $pdfFileName = 'indigency_' . ($record->reference_number ?? $record->id) . '.pdf';
            $pdfPath = $outputDir . '/' . $pdfFileName;
            try {
                app(\App\Services\DocumentPdfService::class)->convertDocxToPdf($docxPath, $pdfPath);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('PDF printing failed', [
                    'module' => class_basename(static::class),
                    'record_id' => $record->id,
                    'error' => $e->getMessage(),
                ]);

                return back()->with('error', 'The Word document was created, but PDF printing failed. Please check the document service logs.');
            }

            @unlink($docxPath);

            return response()->file($pdfPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="certification_of_indigency.pdf"',
            ])->deleteFileAfterSend(true);
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }
}