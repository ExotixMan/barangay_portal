<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Residents;
use App\Models\ResidentActivity;
use App\Models\ResidentProfileCorrectionConcern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules\Password as PasswordRules;
use App\Services\AdminSettingsService;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Residents::withTrashed();

        $search = preg_replace('/[^a-zA-Z0-9\s\-@.]/', '', $request->search ?? ''); 

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('firstname','like',"%$search%")
                ->orWhere('lastname','like',"%$search%")
                ->orWhere('email','like',"%$search%")
                ->orWhere('username','like',"%$search%");
            });
        }

        if ($request->status === 'active') {
            $query->whereNull('deleted_at');
        }

        if ($request->status === 'deleted') {
            $query->onlyTrashed();
        }

        if ($request->age_range) {
            switch ($request->age_range) {
                case 'minor':
                    $query->whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) < 18");
                    break;
                case 'adult':
                    $query->whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) BETWEEN 18 AND 59");
                    break;
                case 'senior':
                    $query->whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) >= 60");
                    break;
            }
        }

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['firstname', 'username', 'age', 'created_at'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if (!in_array(strtolower($direction), ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $query->orderByRaw("
            CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM resident_profile_correction_concerns
                    WHERE resident_profile_correction_concerns.resident_id = residents.id
                    AND resident_profile_correction_concerns.status = 'pending'
                )
                THEN 0
                ELSE 1
            END ASC
        ");

        if ($sort === 'age') {
            $query->orderByRaw(
                "EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) $direction"
            );
        } else {
            $query->orderBy($sort, $direction);
        }

        $residents = $query->paginate(20);

        
        $totalResidents = Residents::withTrashed()->count();

        $lastMonth = Carbon::now()->subMonth();

        $thisMonth = Carbon::now();
        $threeMonthsAgo = $thisMonth->copy()->subMonths(3);
        
        $totalLastThreeMonths = Residents::withTrashed()
            ->whereBetween('created_at', [$threeMonthsAgo->startOfMonth(), $thisMonth->copy()->subMonth()->endOfMonth()])
            ->count();
        
        $threeMonthAverage = $totalLastThreeMonths / 3;
        
        $totalThisMonth = Residents::withTrashed()
            ->whereMonth('created_at', $thisMonth->month)
            ->whereYear('created_at', $thisMonth->year)
            ->count();

        $growthPercentage = 0;

        if ($threeMonthAverage > 0) {
            $growthPercentage = (($totalThisMonth - $threeMonthAverage) / $threeMonthAverage) * 100;
            
            $growthPercentage = max(-100, min(100, $growthPercentage));
        }

        $activeResidents = Residents::whereNull('deleted_at')->count();

        $activePercentage = $totalResidents > 0 ? ($activeResidents / $totalResidents) * 100 : 0;

        $newResidents = Residents::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $yesterdayCount = Residents::whereDate('created_at', Carbon::yesterday())->count();

        $seniorResidents = Residents::whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) >= 60")->count();

        $seniorPercentage = $totalResidents > 0 ? ($seniorResidents / $totalResidents) * 100 : 0;

        $withCorrection = ResidentProfileCorrectionConcern::where('status', 'pending')->get();

        return view('admin.admin_resident', compact('residents', 'withCorrection' ,'totalResidents', 'growthPercentage', 'activeResidents', 'activePercentage', 'newResidents', 'yesterdayCount', 'seniorResidents', 'seniorPercentage'));
    }
    
    public function store(Request $request)
    {
        
        session()->flash('form_type', 'add');

        $request->merge([
            'contact' => preg_replace('/\D+/', '', (string) $request->input('contact')),
        ]);

        $validated = $request->validate([
            'firstname' => ['required','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'middlename' => ['nullable','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'lastname'  => ['required','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'suffix' => ['nullable','string','max:50','regex:/^[a-zA-Z\s\.,]+$/'],
            'username'  => ['required','string','min:3','max:255','regex:/^[a-zA-Z0-9_]+$/','unique:residents,username'],
            'email'     => 'required|email|max:255|unique:residents,email',
            'birthdate' => ['required', 'date', 'before:today', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'gender' => ['required', 'string', 'in:Male,Female,Other,Prefer not to say'],
            'civil_status' => ['required', 'string', 'in:Single,Married,Widowed,Separated,Divorced'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'password'  => ['required','confirmed', 'regex:/^\S+$/', PasswordRules::min(8)->mixedCase()->numbers()->symbols()],
            'contact'   => ['required','string','regex:/^09[0-9]{9}$/'],
            'address'   => 'required|string|max:500',
            'valid_id' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'valid_id_back' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'firstname.regex'   => 'First name can only contain letters, spaces, and hyphens.',
            'middlename.regex'  => 'Middle name can only contain letters, spaces, and hyphens.',
            'lastname.regex'    => 'Last name can only contain letters, spaces, and hyphens.',
            'suffix.regex'      => 'Suffix can only contain letters, spaces, and periods.',
            'username.regex'    => 'Username can only contain letters, numbers, and underscores.',
            'username.min'      => 'Username must be at least 3 characters.',
            'birthdate.before_or_equal' => 'Resident must be 18 years old or above.',
            'contact.regex'     => 'Contact number must be in the format 09XXXXXXXXX (11 digits).',
            'password.regex'    => 'Password cannot contain spaces.',
            'password.*'        => 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.',
            'valid_id.required' => 'Please upload the front side of the resident\'s valid ID.',
            'valid_id_back.required' => 'Please upload the back side of the resident\'s valid ID.',
            'valid_id.mimes' => 'The front valid ID must be JPG, JPEG, PNG, or PDF.',
            'valid_id_back.mimes' => 'The back valid ID must be JPG, JPEG, PNG, or PDF.',
            'valid_id.max' => 'The front valid ID must not exceed 5 MB.',
            'valid_id_back.max' => 'The back valid ID must not exceed 5 MB.',
        ]);

        $uploadedPaths = [];

        try {
            $frontPath = $this->storeResidentValidIdFile($request, 'valid_id');
            $uploadedPaths[] = $frontPath;

            $backPath = $this->storeResidentValidIdFile($request, 'valid_id_back');
            $uploadedPaths[] = $backPath;

            $resident = Residents::create([
                'firstname' => $validated['firstname'],
                'middlename' => $validated['middlename'] ?? null,
                'lastname' => $validated['lastname'],
                'suffix' => $validated['suffix'] ?? null,
                'email' => $validated['email'],
                'address' => $validated['address'],
                'birthdate' => $validated['birthdate'],
                'gender' => $validated['gender'],
                'civil_status' => $validated['civil_status'],
                'place_of_birth' => $validated['place_of_birth'],
                'contact' => $validated['contact'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'valid_id' => $frontPath,
                'valid_id_back' => $backPath,
                'valid_id_verified' => false,
                'valid_id_status' => 'for_verification',
                'valid_id_verified_at' => null,
                'valid_id_verified_by' => null,
                'valid_id_rejection_reason' => null,
            ]);
        } catch (\Throwable $e) {
            foreach ($uploadedPaths as $path) {
                $this->deleteResidentValidIdFile($path);
            }

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Resident could not be added because the valid ID files could not be saved. Please try again.');
        }

        $this->log($resident, 'Created');

        return redirect()->route('admin.residents.index')
            ->with('success', 'Resident added successfully.');
    }

    public function update(Request $request, int $id)
    {
        $resident = Residents::withTrashed()->findOrFail($id);

        if ($request->filled('correction_id')) {
            $correction = ResidentProfileCorrectionConcern::where('id', $request->input('correction_id'))
                ->where('resident_id', $resident->id)
                ->firstOrFail();

            $validated = $request->validate([
                'correction_id' => ['required', 'integer'],
                'resolution_status' => ['required', 'in:resolved,rejected'],
                'remarks' => ['required', 'string', 'max:500'],
                'notify_email' => ['nullable', 'boolean'],
            ], [
                'resolution_status.required' => 'Please select a decision.',
                'resolution_status.in' => 'Invalid correction decision.',
                'remarks.required' => 'Remarks are required.',
                'remarks.max' => 'Remarks must not exceed 500 characters.',
            ]);

            if (!in_array(strtolower((string) $correction->status), [
                'pending',
                'under_review',
                'in_review',
                'reviewing',
                ''
            ], true)) {
                return back()->with(
                    'error',
                    'This correction has already been reviewed.'
                );
            }

            $decision = $validated['resolution_status'];
            $remarks = trim($validated['remarks']);

            if ($decision === 'resolved') {
                $field = strtolower(trim((string) $correction->field_name));

                $fieldAliases = [
                    'first name' => 'firstname',
                    'firstname' => 'firstname',

                    'middle name' => 'middlename',
                    'middlename' => 'middlename',

                    'last name' => 'lastname',
                    'lastname' => 'lastname',

                    'suffix' => 'suffix',

                    'username' => 'username',

                    'email' => 'email',

                    'birthdate' => 'birthdate',
                    'birth date' => 'birthdate',

                    'contact' => 'contact',
                    'contact number' => 'contact',

                    'address' => 'address',
                ];

                $residentField = $fieldAliases[$field] ?? null;

                if (!$residentField) {
                    return back()->with(
                        'error',
                        'The requested correction field "' . $correction->field_name . '" cannot be updated automatically.'
                    );
                }

                $correctValue = trim((string) $correction->correct_value);

                if (in_array($residentField, ['firstname', 'middlename', 'lastname'], true)) {
                    if ($residentField === 'middlename' && $correctValue === '') {
                        $correctValue = null;
                    } elseif (
                        $correctValue !== null &&
                        !preg_match('/^[a-zA-Z\s\-\.]+$/', $correctValue)
                    ) {
                        return back()->with(
                            'error',
                            'The requested name correction contains invalid characters.'
                        );
                    }
                }

                if ($residentField === 'suffix') {
                    if (
                        $correctValue !== '' &&
                        !preg_match('/^[a-zA-Z\s\.,]+$/', $correctValue)
                    ) {
                        return back()->with(
                            'error',
                            'The requested suffix contains invalid characters.'
                        );
                    }

                    $correctValue = $correctValue !== '' ? $correctValue : null;
                }

                if ($residentField === 'username') {
                    if (
                        !preg_match('/^[a-zA-Z0-9_]+$/', $correctValue) ||
                        strlen($correctValue) < 3
                    ) {
                        return back()->with(
                            'error',
                            'The requested username must contain at least 3 letters, numbers, or underscores.'
                        );
                    }

                    $usernameExists = Residents::withTrashed()
                        ->where('username', $correctValue)
                        ->where('id', '!=', $resident->id)
                        ->exists();

                    if ($usernameExists) {
                        return back()->with(
                            'error',
                            'The requested username is already being used by another resident.'
                        );
                    }
                }

                if ($residentField === 'email') {
                    if (!filter_var($correctValue, FILTER_VALIDATE_EMAIL)) {
                        return back()->with(
                            'error',
                            'The requested email address is invalid.'
                        );
                    }

                    $emailExists = Residents::withTrashed()
                        ->where('email', $correctValue)
                        ->where('id', '!=', $resident->id)
                        ->exists();

                    if ($emailExists) {
                        return back()->with(
                            'error',
                            'The requested email address is already being used by another resident.'
                        );
                    }
                }

                if ($residentField === 'birthdate') {
                    try {
                        $birthdate = Carbon::parse($correctValue);

                        if ($birthdate->isToday() || $birthdate->isFuture()) {
                            return back()->with(
                                'error',
                                'The requested birthdate must be a valid date before today.'
                            );
                        }

                        if ($birthdate->gt(now()->subYears(18))) {
                            return back()->with(
                                'error',
                                'The resident must be at least 18 years old.'
                            );
                        }

                        $correctValue = $birthdate->format('Y-m-d');
                    } catch (\Throwable $e) {
                        return back()->with(
                            'error',
                            'The requested birthdate is invalid.'
                        );
                    }
                }

                if ($residentField === 'contact') {
                    $correctValue = preg_replace('/\D+/', '', $correctValue);

                    if (!preg_match('/^09[0-9]{9}$/', $correctValue)) {
                        return back()->with(
                            'error',
                            'The requested contact number must be in the format 09XXXXXXXXX.'
                        );
                    }
                }

                if ($residentField === 'address') {
                    if ($correctValue === '' || strlen($correctValue) > 500) {
                        return back()->with(
                            'error',
                            'The requested address is invalid.'
                        );
                    }
                }

                $resident->update([
                    $residentField => $correctValue,
                ]);
            }

            $correction->update([
                'status' => $decision === 'resolved' ? 'resolved' : 'rejected',
                'reviewed_by' => auth('admin')->id(),
                'reviewed_at' => now('Asia/Manila'),
                'remarks' => $remarks,
            ]);

            $this->log(
                $resident,
                $decision === 'resolved'
                    ? 'Profile Correction Resolved'
                    : 'Profile Correction Rejected'
            );

            if (auth('admin')->check()) {
                \App\Models\AdminActivityLog::create([
                    'user_id' => auth('admin')->id(),
                    'action' => $decision === 'resolved'
                        ? 'UPDATE'
                        : 'REJECT',
                    'module' => 'Residents',
                    'details' => [
                        'resident_id' => $resident->id,
                        'resident_name' => $resident->full_name,
                        'correction_id' => $correction->id,
                        'field' => $correction->field_name,
                        'old_value' => $correction->current_value,
                        'new_value' => $correction->correct_value,
                        'decision' => $decision,
                        'remarks' => $remarks,
                        'reviewed_by' => auth('admin')->user()?->full_name ?? 'Admin',
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            $notificationResult = [
                'email' => 'skipped',
            ];

            $shouldNotify = $request->boolean('notify_email');

            if ($shouldNotify && !empty($resident->email)) {
                $residentName = trim(
                    (string) ($resident->full_name ?? (
                        $resident->firstname . ' ' . $resident->lastname
                    ))
                ) ?: 'Resident';

                $fieldLabel = ucwords(
                    str_replace(
                        ['_', '-'],
                        ' ',
                        (string) $correction->field_name
                    )
                );

                if ($decision === 'resolved') {
                    $message =
                        "Hello {$residentName},\n\n" .
                        "Your profile correction request for {$fieldLabel} has been approved and corrected.\n\n" .
                        "Previous value: {$correction->current_value}\n" .
                        "Updated value: {$correction->correct_value}\n\n" .
                        "Remarks from Barangay Staff:\n{$remarks}\n\n" .
                        "Your resident profile has been updated successfully.\n\n" .
                        "Barangay Hulong Duhat";
                    
                    $subject = 'Profile Correction Approved';
                } else {
                    $message =
                        "Hello {$residentName},\n\n" .
                        "Your profile correction request for {$fieldLabel} was not approved.\n\n" .
                        "Requested correction: {$correction->correct_value}\n\n" .
                        "Remarks from Barangay Staff:\n{$remarks}\n\n" .
                        "Please review the remarks and contact the barangay office if you need further assistance.\n\n" .
                        "Barangay Hulong Duhat";

                    $subject = 'Profile Correction Request Rejected';
                }

                try {
                    app(\App\Services\EmailDeliveryService::class)->sendText($resident->email, $subject, $message);

                    $notificationResult['email'] = 'sent';

                    $this->logNotification(
                        'email',
                        (string) $resident->email,
                        'sent',
                        $message,
                        $resident->id,
                        null
                    );
                } catch (\Throwable $e) {
                    $notificationResult['email'] = 'failed';

                    Log::warning(
                        'Failed to send profile correction notification email.',
                        [
                            'resident_id' => $resident->id,
                            'correction_id' => $correction->id,
                            'email' => $resident->email,
                            'error' => $e->getMessage(),
                        ]
                    );

                    $this->logNotification(
                        'email',
                        (string) $resident->email,
                        'failed',
                        $message,
                        $resident->id,
                        $e->getMessage()
                    );
                }
            }

            $emailMessage = match ($notificationResult['email']) {
                'sent' => ' Resident notification email sent.',
                'failed' => ' The correction was saved, but the resident notification email failed.',
                default => ' No notification email was sent.',
            };

            return back()->with(
                'success',
                ($decision === 'resolved'
                    ? 'Profile correction approved and the resident information was updated.'
                    : 'Profile correction rejected.')
                . $emailMessage
            );
        }

        $previousValidIdStatus = (string) ($resident->valid_id_status ?? '');
        $previousValidIdRemarks = (string) ($resident->valid_id_rejection_reason ?? '');

        session()->flash('form_type', 'edit_' . $resident->id);

        $request->merge([
            'contact' => preg_replace(
                '/\D+/',
                '',
                (string) $request->input('contact')
            ),
        ]);

        $validated = $request->validate([
            'firstname' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\-\.]+$/'
            ],
            'middlename' => [
                'nullable',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\-\.]+$/'
            ],
            'lastname' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\-\.]+$/'
            ],
            'suffix' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s\.,]+$/'
            ],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z0-9_]+$/',
                'unique:residents,username,' . $resident->id
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:residents,email,' . $resident->id
            ],
            'birthdate' => [
                'required',
                'date',
                'before:today',
                'before_or_equal:' . now()->subYears(18)->toDateString()
            ],
            'gender' => ['nullable', 'string', 'max:50'],
            'civil_status' => ['nullable', 'string', 'max:50'],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'contact' => [
                'required',
                'string',
                'regex:/^09[0-9]{9}$/'
            ],
            'address' => 'required|string|max:500',

            'valid_id' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],
            'valid_id_back' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],
            'valid_id_status' => [
                'nullable',
                'in:for_verification,verified,rejected'
            ],
            'valid_id_rejection_reason' => [
                'nullable',
                'string',
                'max:500',
                'required_if:valid_id_status,rejected'
            ],
        ], [
            'firstname.regex' => 'First name can only contain letters, spaces, and hyphens.',
            'middlename.regex' => 'Middle name can only contain letters, spaces, and hyphens.',
            'lastname.regex' => 'Last name can only contain letters, spaces, and hyphens.',
            'suffix.regex' => 'Suffix can only contain letters, spaces, and periods.',
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
            'username.min' => 'Username must be at least 3 characters.',
            'birthdate.before_or_equal' => 'Resident must be 18 years old or above.',
            'contact.regex' => 'Contact number must be in the format 09XXXXXXXXX (11 digits).',
            'valid_id.mimes' => 'The front valid ID must be JPG, JPEG, PNG, or PDF.',
            'valid_id_back.mimes' => 'The back valid ID must be JPG, JPEG, PNG, or PDF.',
            'valid_id_status.in' => 'Please select a valid ID status.',
            'valid_id_rejection_reason.required_if' => 'Remarks are required when rejecting a valid ID.',
        ]);

        $updateData = [
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'] ?? null,
            'lastname' => $validated['lastname'],
            'suffix' => $validated['suffix'] ?? null,
            'username' => $validated['username'],
            'email' => $validated['email'],
            'contact' => $validated['contact'],
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'] ?? null,
            'civil_status' => $validated['civil_status'] ?? null,
            'place_of_birth' => $validated['place_of_birth'] ?? null,
            'address' => $validated['address'],
        ];

        foreach (['valid_id', 'valid_id_back'] as $field) {
            if ($request->hasFile($field)) {
                $this->deleteResidentValidIdFile(
                    (string) $resident->{$field}
                );

                $updateData[$field] = $this->storeResidentValidIdFile(
                    $request,
                    $field
                );

                $updateData['valid_id_verified'] = 'false';
                $updateData['valid_id_status'] = 'for_verification';
                $updateData['valid_id_verified_at'] = null;
                $updateData['valid_id_verified_by'] = null;
                $updateData['valid_id_rejection_reason'] = null;
            }
        }

        if ($request->filled('valid_id_status')) {
            $status = (string) $validated['valid_id_status'];

            if ($status === 'verified') {
                $hasFront =
                    $request->hasFile('valid_id') ||
                    !empty($resident->valid_id);

                $hasBack =
                    $request->hasFile('valid_id_back') ||
                    !empty($resident->valid_id_back);

                if (!$hasFront || !$hasBack) {
                    return back()
                        ->withErrors([
                            'valid_id_status' =>
                                'Front and back valid ID images are required before approval.'
                        ])
                        ->withInput();
                }

                $updateData['valid_id_verified'] = 'true';
                $updateData['valid_id_status'] = 'verified';
                $updateData['valid_id_verified_at'] = now('Asia/Manila');
                $updateData['valid_id_verified_by'] = auth('admin')->id();
                $updateData['valid_id_rejection_reason'] = null;
            }

            if ($status === 'rejected') {
                $updateData['valid_id_verified'] = 'false';
                $updateData['valid_id_status'] = 'rejected';
                $updateData['valid_id_verified_at'] = null;
                $updateData['valid_id_verified_by'] = null;
                $updateData['valid_id_rejection_reason'] =
                    $validated['valid_id_rejection_reason'] ?? null;
            }

            if ($status === 'for_verification') {
                $updateData['valid_id_verified'] = 'false';
                $updateData['valid_id_status'] = 'for_verification';
                $updateData['valid_id_verified_at'] = null;
                $updateData['valid_id_verified_by'] = null;
                $updateData['valid_id_rejection_reason'] = null;
            }
        }

        $resident->update($updateData);

        $this->log($resident, 'Updated');

        $successMessage = 'Resident updated successfully.';

        if (
            ($updateData['valid_id_status'] ?? null) === 'rejected' &&
            (
                $previousValidIdStatus !== 'rejected' ||
                $previousValidIdRemarks !==
                (string) ($updateData['valid_id_rejection_reason'] ?? '')
            )
        ) {
            $notificationResult = $this->notifyResidentValidIdRejected(
                $resident->fresh(),
                (string) (
                    $updateData['valid_id_rejection_reason'] ??
                    'No remarks provided.'
                )
            );

            $successMessage .= ' ' .
                $this->formatValidIdNotificationSummary($notificationResult);
        }

        return back()->with(
            'success',
            trim($successMessage)
        );
    }

    public function destroy(int $id)
    {
        $resident = Residents::findOrFail($id);
        $resident->delete();
        $this->log($resident, 'Deactivated');
        $message = 'Resident moved to archive.';
    
        return redirect()->back()->with('success', $message);
    }

    public function restore(int $id)
    {
        $resident = Residents::withTrashed()->findOrFail($id);
        $resident->restore();
        $this->log($resident, 'Restored');
        
        return redirect()->back()->with('success', 'Resident restored successfully.');
    }

    public function verifyValidId(Request $request, int $id)
    {
        $resident = Residents::withTrashed()->findOrFail($id);

        if (empty($resident->valid_id) || empty($resident->valid_id_back)) {
            return redirect()->back()->with('error', 'Front and back valid ID images are required before approval.');
        }

        $resident->update([
            'valid_id_verified' => 'true',
            'valid_id_status' => 'verified',
            'valid_id_verified_at' => now('Asia/Manila'),
            'valid_id_verified_by' => auth('admin')->id(),
            'valid_id_rejection_reason' => null,
        ]);

        $this->log($resident, 'Valid ID Approved');

        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'APPROVE',
                'module' => 'Residents',
                'details' => [
                    'resident_id' => $resident->id,
                    'resident_name' => $resident->full_name,
                    'action_type' => 'valid_id_approved',
                    'approved_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        $notificationResult = ['email' => 'skipped'];

        if ($request->boolean('notify_email')) {
            $notificationResult = $this->notifyResidentValidIdApproved($resident->fresh());
        }

        return redirect()->back()->with(
            'success',
            'Valid ID for ' . $resident->full_name . ' has been approved. ' . $this->formatValidIdNotificationSummary($notificationResult)
        );
    }

    public function rejectValidId(Request $request, int $id)
    {
        $validated = $request->validate([
            'valid_id_rejection_reason' => ['required','string','max:500'],
        ], [
            'valid_id_rejection_reason.required' => 'Remarks are required when rejecting a valid ID.',
            'valid_id_rejection_reason.max' => 'Remarks must not exceed 500 characters.',
        ]);

        $resident = Residents::withTrashed()->findOrFail($id);

        if (empty($resident->valid_id) && empty($resident->valid_id_back)) {
            return redirect()->back()->with('error', 'This resident has no valid ID images to reject.');
        }

        $resident->update([
            'valid_id_verified' => 'false',
            'valid_id_status' => 'rejected',
            'valid_id_verified_at' => null,
            'valid_id_verified_by' => null,
            'valid_id_rejection_reason' => $validated['valid_id_rejection_reason'],
        ]);

        $this->log($resident, 'Valid ID Rejected');

        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'REJECT',
                'module' => 'Residents',
                'details' => [
                    'resident_id' => $resident->id,
                    'resident_name' => $resident->full_name,
                    'action_type' => 'valid_id_rejected',
                    'remarks' => $validated['valid_id_rejection_reason'],
                    'rejected_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        $notificationResult = $this->notifyResidentValidIdRejected(
            $resident->fresh(),
            (string) $validated['valid_id_rejection_reason']
        );

        return redirect()->back()->with(
            'success',
            'Valid ID for ' . $resident->full_name . ' has been rejected. ' . $this->formatValidIdNotificationSummary($notificationResult)
        );
    }

    private function notifyResidentValidIdRejected(Residents $resident, string $remarks): array
    {
        $result = [
            'email' => 'skipped',
            
        ];

        $residentName = trim((string) ($resident->full_name ?? ($resident->firstname . ' ' . $resident->lastname))) ?: 'Resident';
        $remarks = trim($remarks) !== '' ? trim($remarks) : 'No remarks provided.';

        $message = "Hello {$residentName}, your valid ID verification was rejected. Remarks: {$remarks} Please log in to your account and upload a clearer front and back copy of your valid ID. - Barangay Hulong Duhat";

        if (!empty($resident->email)) {
            try {
                app(\App\Services\EmailDeliveryService::class)->sendText($resident->email, 'Valid ID Verification Rejected', $message);

                $result['email'] = 'sent';
                $this->logNotification('email', (string) $resident->email, 'sent', $message, $resident->id, null);
            } catch (\Throwable $e) {
                $result['email'] = 'failed';
                Log::warning('Failed to send valid ID rejection email.', [
                    'resident_id' => $resident->id,
                    'email' => $resident->email,
                    'error' => $e->getMessage(),
                ]);
                $this->logNotification('email', (string) $resident->email, 'failed', $message, $resident->id, $e->getMessage());
            }
        }

        

        

        

        

        

        

        

        

        

        

        

        

        

        

        

        return $result;
    }

    private function notifyResidentValidIdApproved(Residents $resident): array
    {
        $result = ['email' => 'skipped'];

        if (empty($resident->email)) {
            return $result;
        }

        $residentName = trim((string) ($resident->full_name ?? ($resident->firstname . ' ' . $resident->lastname))) ?: 'Resident';

        $message = "Hello {$residentName}, your valid ID has been verified and approved. Your resident account is now fully verified. - Barangay Hulong Duhat";

        try {
            app(\App\Services\EmailDeliveryService::class)->sendText($resident->email, 'Valid ID Verified', $message);

            $result['email'] = 'sent';
            $this->logNotification('email', (string) $resident->email, 'sent', $message, $resident->id, null);
        } catch (\Throwable $e) {
            $result['email'] = 'failed';
            Log::warning('Failed to send valid ID approval email.', [
                'resident_id' => $resident->id,
                'email' => $resident->email,
                'error' => $e->getMessage(),
            ]);
            $this->logNotification('email', (string) $resident->email, 'failed', $message, $resident->id, $e->getMessage());
        }

        return $result;
    }

    private function formatValidIdNotificationSummary(array $result): string
    {
        $sent = [];
        $failed = [];

        if (($result['email'] ?? 'skipped') === 'sent') {
            $sent[] = 'email';
        }

        if (($result['sms'] ?? 'skipped') === 'sent') {
            $sent[] = 'SMS';
        }

        if (($result['email'] ?? 'skipped') === 'failed') {
            $failed[] = 'email';
        }

        if (($result['sms'] ?? 'skipped') === 'failed') {
            $failed[] = 'SMS';
        }

        if ($sent && !$failed) {
            return 'Notification sent via ' . implode(' and ', $sent) . '.';
        }

        if ($sent && $failed) {
            return 'Notification sent via ' . implode(' and ', $sent) . ', but ' . implode(' and ', $failed) . ' failed.';
        }

        if ($failed) {
            return 'The ID was rejected, but ' . implode(' and ', $failed) . ' notification failed. Check mail/Semaphore settings.';
        }

        return 'No notification was sent because the resident has no email/contact or SMS settings are missing.';
    }

    private function formatPhilippineMobileNumber(string $contact): ?string
    {
        $digits = preg_replace('/\D+/', '', $contact);

        if (preg_match('/^09\d{9}$/', $digits)) {
            return '63' . substr($digits, 1);
        }

        if (preg_match('/^639\d{9}$/', $digits)) {
            return $digits;
        }

        return null;
    }

    private function logNotification(string $channel, string $recipient, string $status, string $message, int $residentId, ?string $error = null): void
    {
        if (!class_exists(\App\Models\NotificationLog::class)) {
            return;
        }

        try {
            \App\Models\NotificationLog::create([
                'channel' => $channel,
                'recipient' => $recipient,
                'status' => $status,
                'message' => $message,
                'error_message' => $error,
                'reference_type' => Residents::class,
                'reference_id' => $residentId,
                'sent_by' => auth('admin')->id(),
                'sent_at' => $status === 'sent' ? now('Asia/Manila') : null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to write valid ID rejection notification log.', [
                'resident_id' => $residentId,
                'channel' => $channel,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function storeResidentValidIdFile(Request $request, string $field): string
    {
        $file = $request->file($field);

        if (!$file || !$file->isValid()) {
            throw new \RuntimeException('The uploaded file for ' . $field . ' is invalid.');
        }

        $uploadDirectory = public_path('uploads/valid_id/residents');

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = $field . '_' . now('Asia/Manila')->format('Ymd_His') . '_' . uniqid('', true) . '.' . $extension;

        $file->move($uploadDirectory, $filename);

        return 'uploads/valid_id/residents/' . $filename;
    }

    private function deleteResidentValidIdFile(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:residents,id'
        ]);

        $residents = Residents::whereIn('id', $request->ids)->get();

        foreach ($residents as $resident) {
            $resident->delete();
            $this->log($resident, 'Bulk Archived');
        }

        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'BULK_ARCHIVE',
                'module' => 'Residents',
                'details' => [
                    'ids' => $request->ids,
                    'count' => count($request->ids),
                    'archived_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return back()->with('success', 'Selected residents archived.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'exists:residents,id'
        ]);

        $query = Residents::query();

        if ($request->ids) {
            $query->whereIn('id', $request->ids);
        } else {
            $query->whereIn('id', Residents::pluck('id'));
        }

        $residents = $query->get();

        $filename = 'residents_' . now()->format('Y-m-d_His') . '.csv'; 

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($residents) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['ID', 'Name', 'Email', 'Contact', 'Address', 'Username', 'Birthdate', 'Created At']);

            foreach ($residents as $r) {
                $full_name = $r->firstname . ' ' . $r->lastname;
                fputcsv($file, [
                    $r->id, $full_name, $r->email, $r->contact, $r->address, 
                    $r->username, $r->birthdate, $r->created_at
                ]);
            }

            fclose($file);
        };

        if (auth('admin')->check()) {
            $exportCount = Residents::query()->when($request->ids, function($q) use ($request) {
                return $q->whereIn('id', $request->ids);
            })->count();
            
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'EXPORT',
                'module' => 'Residents',
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

    private function log(Residents $resident, string $action) : void
    {
        ResidentActivity::create([
            'residents_id' => $resident->id,
            'action' => $action,
            'description' => $action . " by " . (auth('admin')->user()?->full_name),
        ]);
    }
}