<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\NotificationLog;
use App\Models\ResidentActivity;
use App\Models\ResidentProfileCorrectionConcern;
use App\Models\Residents;
use App\Rules\CaseInsensitiveUnique;
use App\Services\AdminRecordExport;
use App\Services\EmailDeliveryService;
use App\Services\SupabaseStorageService;
use App\Support\ResidentName;
use App\Support\UploadedImageComparator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Illuminate\Validation\ValidationException;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Residents::withTrashed();
        $today = Carbon::today(config('app.timezone'));
        $adultCutoff = $today->copy()->subYearsNoOverflow(18)->toDateString();
        $seniorCutoff = $today->copy()->subYearsNoOverflow(60)->toDateString();

        $search = preg_replace('/[^a-zA-Z0-9\s\-@.]/', '', $request->search ?? '');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%");
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
                    $query->whereDate('birthdate', '>', $adultCutoff);
                    break;
                case 'adult':
                    $query->whereDate('birthdate', '<=', $adultCutoff)->whereDate('birthdate', '>', $seniorCutoff);
                    break;
                case 'senior':
                    $query->whereDate('birthdate', '<=', $seniorCutoff);
                    break;
            }
        }

        if ($request->classification) {
            switch ($request->classification) {
                case 'student':
                    $query->whereRaw('is_student IS TRUE');
                    break;
                case 'student_pending':
                    $query->where('student_status', 'pending');
                    break;
                case 'pwd':
                    $query->whereRaw('is_pwd IS TRUE');
                    break;
                case 'pwd_pending':
                    $query->where('pwd_status', 'pending');
                    break;
                case 'senior':
                    $query->whereDate('birthdate', '<=', $seniorCutoff);
                    break;
            }
        }

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['firstname', 'username', 'age', 'created_at'];

        if (! in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if (! in_array(strtolower($direction), ['asc', 'desc'])) {
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
            $query->orderBy('birthdate', strtolower($direction) === 'asc' ? 'desc' : 'asc');
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

        $seniorResidents = Residents::whereDate('birthdate', '<=', $seniorCutoff)->count();

        $seniorPercentage = $totalResidents > 0 ? ($seniorResidents / $totalResidents) * 100 : 0;

        $withCorrection = ResidentProfileCorrectionConcern::where('status', 'pending')->get();

        return view('admin.admin_resident', compact('residents', 'withCorrection', 'totalResidents', 'growthPercentage', 'activeResidents', 'activePercentage', 'newResidents', 'yesterdayCount', 'seniorResidents', 'seniorPercentage'));
    }

    public function store(Request $request)
    {

        session()->flash('form_type', 'add');

        $request->merge([
            'contact' => preg_replace('/\D+/', '', (string) $request->input('contact')),
        ]);

        $validated = $request->validate([
            'firstname' => ['required', 'string', 'min:2', 'max:255', "regex:/^[\pL\s\'\.,-]+$/u"],
            'middlename' => ['nullable', 'string', 'min:2', 'max:255', "regex:/^[\pL\s\'\.,-]+$/u"],
            'lastname' => ['required', 'string', 'min:2', 'max:255', "regex:/^[\pL\s\'\.,-]+$/u"],
            'suffix' => ['nullable', 'string', 'max:10', 'in:Jr.,Sr.,II,III,IV,V'],
            'username' => ['required', 'string', 'min:4', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/', new CaseInsensitiveUnique('residents', 'username')],
            'email' => ['required', 'email', 'max:255', new CaseInsensitiveUnique('residents', 'email')],
            'birthdate' => ['required', 'date', 'before:today', 'before_or_equal:'.now()->subYears(18)->toDateString()],
            'gender' => ['required', 'string', 'in:Male,Female,Prefer not to say'],
            'civil_status' => ['required', 'string', 'in:Single,Married,Widowed,Separated'],
            'place_of_birth' => ['required', 'string', 'max:255', "regex:/^[\pL\s\'\.,-]+$/u"],
            'password' => ['required', 'confirmed', 'regex:/^\S+$/', PasswordRules::min(8)->mixedCase()->numbers()->symbols()],
            'contact' => ['required', 'string', 'regex:/^09[0-9]{9}$/', 'unique:residents,contact'],
            'address' => 'required|string|max:255',
            'valid_id' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'valid_id_back' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'declare_pwd' => ['nullable', 'boolean'],
            'pwd_id_front' => ['required_if:declare_pwd,1', 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'pwd_id_back' => ['required_if:declare_pwd,1', 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'declare_student' => ['nullable', 'boolean'],
            'student_proof' => ['required_if:declare_student,1', 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'firstname.regex' => 'First name can only contain letters, spaces, and hyphens.',
            'middlename.regex' => 'Middle name can only contain letters, spaces, and hyphens.',
            'lastname.regex' => 'Last name can only contain letters, spaces, and hyphens.',
            'suffix.regex' => 'Suffix can only contain letters, spaces, and periods.',
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
            'username.min' => 'Username must be at least 4 characters.',
            'birthdate.before_or_equal' => 'Resident must be 18 years old or above.',
            'contact.regex' => 'Contact number must be in the format 09XXXXXXXXX (11 digits).',
            'password.regex' => 'Password cannot contain spaces.',
            'password.*' => 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.',
            'valid_id.required' => 'Please upload the front side of the resident\'s valid ID.',
            'valid_id_back.required' => 'Please upload the back side of the resident\'s valid ID.',
            'valid_id.mimes' => 'The front valid ID must be JPG, JPEG, PNG, or PDF.',
            'valid_id_back.mimes' => 'The back valid ID must be JPG, JPEG, PNG, or PDF.',
            'valid_id.max' => 'The front valid ID must not exceed 5 MB.',
            'valid_id_back.max' => 'The back valid ID must not exceed 5 MB.',
            'pwd_id_front.required_if' => "Upload the front of the resident's PWD ID card.",
            'pwd_id_back.required_if' => "Upload the back of the resident's PWD ID card.",
            'student_proof.required_if' => 'Upload a current school ID, registration form, or certificate of enrollment.',
        ]);

        if (UploadedImageComparator::areSameOrNearDuplicate(
            $request->file('valid_id'),
            $request->file('valid_id_back')
        )) {
            return back()
                ->withInput()
                ->withErrors(['valid_id_back' => 'Valid ID back appears to be the same picture as the front. Upload the actual back side.']);
        }

        if ($request->boolean('declare_pwd') && UploadedImageComparator::areSameOrNearDuplicate(
            $request->file('pwd_id_front'),
            $request->file('pwd_id_back')
        )) {
            return back()
                ->withInput()
                ->withErrors(['pwd_id_back' => 'PWD ID back appears to be the same picture as the front. Upload the actual back side.']);
        }

        $declaredPwd = $request->boolean('declare_pwd');
        $declaredStudent = $request->boolean('declare_student');
        $uploadedPaths = [];

        try {
            $frontPath = $this->storeResidentValidIdFile($request, 'valid_id');
            $uploadedPaths[] = $frontPath;

            $backPath = $this->storeResidentValidIdFile($request, 'valid_id_back');
            $uploadedPaths[] = $backPath;

            $pwdFrontPath = null;
            $pwdBackPath = null;
            $studentProofPath = null;

            if ($declaredPwd) {
                $pwdFrontPath = $this->storeResidentClassificationFile($request, 'pwd_id_front', 'pwd');
                $uploadedPaths[] = $pwdFrontPath;
                $pwdBackPath = $this->storeResidentClassificationFile($request, 'pwd_id_back', 'pwd');
                $uploadedPaths[] = $pwdBackPath;
            }

            if ($declaredStudent) {
                $studentProofPath = $this->storeResidentClassificationFile($request, 'student_proof', 'student');
                $uploadedPaths[] = $studentProofPath;
            }

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
                'valid_id_verified' => DB::raw('FALSE'),
                'valid_id_status' => 'for_verification',
                'valid_id_verified_at' => null,
                'valid_id_verified_by' => null,
                'valid_id_rejection_reason' => null,
                'is_pwd' => DB::raw('FALSE'),
                'pwd_status' => $declaredPwd ? 'pending' : 'not_declared',
                'pwd_id_front' => $pwdFrontPath,
                'pwd_id_back' => $pwdBackPath,
                'pwd_verified_at' => null,
                'pwd_verified_by' => null,
                'pwd_rejection_reason' => null,
                'is_student' => DB::raw('FALSE'),
                'student_status' => $declaredStudent ? 'pending' : 'not_declared',
                'student_proof' => $studentProofPath,
                'student_verified_at' => null,
                'student_verified_by' => null,
                'student_rejection_reason' => null,
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

            if (! in_array(strtolower((string) $correction->status), ResidentProfileCorrectionConcern::OPEN_STATUSES, true)) {
                return back()->with(
                    'error',
                    'This correction has already been reviewed.'
                );
            }

            $decision = $validated['resolution_status'];
            $remarks = trim($validated['remarks']);
            $residentChanges = [];

            if ($decision === 'resolved' && ResidentName::isFullNameField($correction->field_name)) {
                // A cached/older review form can approve the resident's request without retyping it.
                $nameInput = $request->all();
                if (! $request->exists('name_firstname') && ! $request->exists('name_lastname')) {
                    foreach (ResidentName::suggestedParts($correction->correct_value, $resident) as $part => $value) {
                        $nameInput['name_'.$part] = $value;
                    }
                }
                $residentChanges = ResidentName::confirmedParts($nameInput);
            } elseif ($decision === 'resolved') {
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
                    'email address' => 'email',

                    'birthdate' => 'birthdate',
                    'birth date' => 'birthdate',

                    'contact' => 'contact',
                    'contact number' => 'contact',

                    'address' => 'address',
                    'complete address' => 'address',
                    'gender' => 'gender',
                    'civil status' => 'civil_status',
                    'civil_status' => 'civil_status',
                    'place of birth' => 'place_of_birth',
                    'place_of_birth' => 'place_of_birth',
                    'birth place' => 'place_of_birth',
                    'birth_place' => 'place_of_birth',
                ];

                $residentField = $fieldAliases[$field] ?? null;

                if (! $residentField) {
                    return back()->with(
                        'error',
                        'The requested correction field "'.$correction->field_name.'" cannot be updated automatically.'
                    );
                }

                $correctValue = trim((string) $correction->correct_value);
                $maximumLength = $residentField === 'suffix' ? 10 : ($residentField === 'username' ? 50 : 255);
                if (mb_strlen($correctValue) > $maximumLength) {
                    return back()->with('error', 'The requested correction is too long. Please submit a value of at most '.$maximumLength.' characters.');
                }

                if (in_array($residentField, ['firstname', 'middlename', 'lastname'], true)) {
                    if ($residentField === 'middlename' && $correctValue === '') {
                        $correctValue = null;
                    } elseif (
                        $correctValue !== null &&
                        ! preg_match(ResidentName::PATTERN, $correctValue)
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
                        ! preg_match('/^[a-zA-Z\s\.,]+$/', $correctValue)
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
                        ! preg_match('/^[a-zA-Z0-9_.-]+$/', $correctValue) ||
                        strlen($correctValue) < 4
                    ) {
                        return back()->with(
                            'error',
                            'The requested username must contain 4 to 50 letters, numbers, dots, hyphens, or underscores.'
                        );
                    }

                    $usernameExists = Residents::withTrashed()
                        ->whereRaw('LOWER(username) = ?', [mb_strtolower($correctValue)])
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
                    if (! filter_var($correctValue, FILTER_VALIDATE_EMAIL)) {
                        return back()->with(
                            'error',
                            'The requested email address is invalid.'
                        );
                    }

                    $emailExists = Residents::withTrashed()
                        ->whereRaw('LOWER(email) = ?', [mb_strtolower($correctValue)])
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
                        if (! validator(['birthdate' => $correctValue], ['birthdate' => 'required|date_format:Y-m-d'])->passes()) {
                            return back()->with('error', 'Please use YYYY-MM-DD for the requested birthdate.');
                        }
                        $birthdate = Carbon::parse($correctValue);

                        if ($birthdate->isToday() || $birthdate->isFuture()) {
                            return back()->with(
                                'error',
                                'The requested birthdate must be a valid date before today.'
                            );
                        }

                        if ($birthdate->gt(now()->subYearsNoOverflow(18)->startOfDay())) {
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

                    if (! preg_match('/^09[0-9]{9}$/', $correctValue)) {
                        return back()->with(
                            'error',
                            'The requested contact number must be in the format 09XXXXXXXXX.'
                        );
                    }
                }

                if ($residentField === 'contact' && Residents::withTrashed()->where('contact', $correctValue)->whereKeyNot($resident->id)->exists()) {
                    return back()->with('error', 'The requested contact number is already being used by another resident.');
                }

                if ($residentField === 'address') {
                    if ($correctValue === '' || mb_strlen($correctValue) > 255) {
                        return back()->with(
                            'error',
                            'The requested address is invalid.'
                        );
                    }
                }

                if ($residentField === 'gender') {
                    $allowedGenders = ['Male', 'Female', 'Prefer not to say'];
                    if (! in_array($correctValue, $allowedGenders, true)) {
                        return back()->with('error', 'The requested gender is invalid.');
                    }
                }

                if ($residentField === 'civil_status') {
                    $allowedStatuses = ['Single', 'Married', 'Widowed', 'Separated'];
                    if (! in_array($correctValue, $allowedStatuses, true)) {
                        return back()->with('error', 'The requested civil status is invalid.');
                    }
                }

                if ($residentField === 'place_of_birth') {
                    if ($correctValue === '' || strlen($correctValue) > 255 || ! preg_match('/^[\pL\s\'\.,-]+$/u', $correctValue)) {
                        return back()->with('error', 'The requested place of birth is invalid.');
                    }
                }

                $residentChanges = [
                    $residentField => $correctValue,
                ];
                if ($residentField === 'email' && mb_strtolower($correctValue) !== mb_strtolower((string) $resident->email)) {
                    $residentChanges['email_verified_at'] = null;
                }
                if ($residentField === 'contact' && $correctValue !== $resident->contact) {
                    $residentChanges += ['phone_verified' => false, 'phone_otp' => null, 'phone_otp_expires_at' => null];
                }
            }

            $resolvedValue = $decision === 'resolved'
                ? (ResidentName::isFullNameField($correction->field_name)
                    ? implode(' ', array_filter($residentChanges))
                    : (string) reset($residentChanges))
                : null;

            $appliedValue = $resolvedValue ?? $correction->correct_value;
            try {
                $hasSnapshotColumn = Schema::hasColumn('resident_profile_correction_concerns', 'resolved_value');
                DB::transaction(function () use ($resident, $correction, $residentChanges, $decision, $remarks, $resolvedValue, $hasSnapshotColumn, $appliedValue, $request) {
                    $lockedConcern = ResidentProfileCorrectionConcern::whereKey($correction->id)->lockForUpdate()->firstOrFail();
                    if (! in_array(strtolower((string) $lockedConcern->status), ResidentProfileCorrectionConcern::OPEN_STATUSES, true)) {
                        throw ValidationException::withMessages(['correction_id' => 'This correction has already been reviewed. Please reload the resident record.']);
                    }
                    if ($residentChanges) {
                        $resident->update($residentChanges);
                    }
                    $review = [
                        'status' => $decision === 'resolved' ? 'resolved' : 'rejected',
                        'reviewed_by' => auth('admin')->id(),
                        'reviewed_at' => now('Asia/Manila'),
                        'remarks' => $remarks,
                    ];
                    if ($hasSnapshotColumn) {
                        $review['resolved_value'] = $resolvedValue;
                    }
                    $lockedConcern->update($review);
                    $this->log(
                        $resident,
                        $decision === 'resolved'
                            ? 'Profile Correction Resolved'
                            : 'Profile Correction Rejected'
                    );

                    if (auth('admin')->check()) {
                        $audit = AdminActivityLog::create([
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
                                'new_value' => $appliedValue,
                                'requested_value' => $correction->correct_value,
                                'decision' => $decision,
                                'remarks' => $remarks,
                                'reviewed_by' => auth('admin')->user()?->full_name ?? 'Admin',
                            ],
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                        ]);
                        if (! $audit->exists) {
                            throw new \RuntimeException('The correction audit could not be saved.');
                        }
                    }
                });
            } catch (ValidationException $e) {
                throw $e;
            } catch (\Throwable $e) {
                Log::error('Profile correction review failed', ['correction_id' => $correction->id, 'resident_id' => $resident->id, 'exception' => $e]);

                return back()->withInput()->withErrors(['correction_id' => 'We could not save this decision. Reload the resident record and try again, or contact the system administrator.']);
            }

            $notificationResult = [
                'email' => 'skipped',
            ];

            $shouldNotify = $request->boolean('notify_email');

            if ($shouldNotify && ! empty($resident->email)) {
                $residentName = trim(
                    (string) ($resident->full_name ?? (
                        $resident->firstname.' '.$resident->lastname
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
                        "Hello {$residentName},\n\n".
                        "Your profile correction request for {$fieldLabel} has been approved and corrected.\n\n".
                        "Previous value: {$correction->current_value}\n".
                        "Updated value: {$appliedValue}\n\n".
                        "Remarks from Barangay Staff:\n{$remarks}\n\n".
                        "Your resident profile has been updated successfully.\n\n".
                        'Barangay Hulong Duhat';

                    $subject = 'Profile Correction Approved';
                } else {
                    $message =
                        "Hello {$residentName},\n\n".
                        "Your profile correction request for {$fieldLabel} was not approved.\n\n".
                        "Requested correction: {$correction->correct_value}\n\n".
                        "Remarks from Barangay Staff:\n{$remarks}\n\n".
                        "Please review the remarks and contact the barangay office if you need further assistance.\n\n".
                        'Barangay Hulong Duhat';

                    $subject = 'Profile Correction Request Rejected';
                }

                try {
                    app(EmailDeliveryService::class)->sendText($resident->email, $subject, $message);

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
                .$emailMessage
            );
        }

        $previousValidIdStatus = (string) ($resident->valid_id_status ?? '');
        $previousValidIdRemarks = (string) ($resident->valid_id_rejection_reason ?? '');

        session()->flash('form_type', 'edit_'.$resident->id);

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
                'regex:/^[a-zA-Z\s\-\.]+$/',
            ],
            'middlename' => [
                'nullable',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\-\.]+$/',
            ],
            'lastname' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\-\.]+$/',
            ],
            'suffix' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s\.,]+$/',
            ],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z0-9_]+$/',
                'unique:residents,username,'.$resident->id,
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:residents,email,'.$resident->id,
            ],
            'birthdate' => [
                'required',
                'date',
                'before:today',
                'before_or_equal:'.now()->subYears(18)->toDateString(),
            ],
            'gender' => ['nullable', 'string', 'max:50'],
            'civil_status' => ['nullable', 'string', 'max:50'],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'contact' => [
                'required',
                'string',
                'regex:/^09[0-9]{9}$/',
            ],
            'address' => 'required|string|max:255',

            'valid_id' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
            'valid_id_back' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
            'valid_id_status' => [
                'nullable',
                'in:for_verification,verified,rejected',
            ],
            'valid_id_rejection_reason' => [
                'nullable',
                'string',
                'max:500',
                'required_if:valid_id_status,rejected',
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
                    ! empty($resident->valid_id);

                $hasBack =
                    $request->hasFile('valid_id_back') ||
                    ! empty($resident->valid_id_back);

                if (! $hasFront || ! $hasBack) {
                    return back()
                        ->withErrors([
                            'valid_id_status' => 'Front and back valid ID images are required before approval.',
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

            $successMessage .= ' '.
                $this->formatValidIdNotificationSummary($notificationResult);
        }

        return back()->with(
            'success',
            trim($successMessage)
        );
    }

    public function updateClassificationStatus(Request $request, int $id, string $type, string $decision)
    {
        abort_unless(in_array($type, ['pwd', 'student'], true), 404);
        abort_unless(in_array($decision, ['verified', 'rejected'], true), 404);

        $resident = Residents::withTrashed()->findOrFail($id);

        $currentStatus = $type === 'pwd'
            ? strtolower((string) ($resident->pwd_status ?? 'not_declared'))
            : strtolower((string) ($resident->student_status ?? 'not_declared'));

        if ($currentStatus !== 'pending') {
            return back()->with(
                'error',
                ucfirst($type).' classification can only be reviewed while its status is pending.'
            );
        }

        $validated = $request->validate([
            'remarks' => [
                $decision === 'rejected' ? 'required' : 'nullable',
                'string',
                'max:500',
            ],
        ], [
            'remarks.required' => 'Please enter rejection remarks before rejecting the classification evidence.',
            'remarks.max' => 'Rejection remarks must not exceed 500 characters.',
        ]);

        $verified = $decision === 'verified';
        $adminId = auth('admin')->id();
        $now = now('Asia/Manila');

        if ($type === 'pwd') {
            if (empty($resident->pwd_id_front) || empty($resident->pwd_id_back)) {
                return back()->with(
                    'error',
                    'PWD ID front and back are required before PWD verification.'
                );
            }

            DB::statement(
                'UPDATE residents
                 SET pwd_status = ?,
                     is_pwd = '.($verified ? 'TRUE' : 'FALSE').',
                     pwd_verified_at = ?,
                     pwd_verified_by = ?,
                     pwd_rejection_reason = ?,
                     updated_at = ?
                 WHERE id = ?',
                [
                    $decision,
                    $verified ? $now : null,
                    $verified ? $adminId : null,
                    $verified ? null : trim((string) $validated['remarks']),
                    $now,
                    $resident->id,
                ]
            );

            $this->log(
                $resident,
                $verified
                    ? 'PWD Classification Verified'
                    : 'PWD Classification Rejected'
            );

            return back()->with(
                'success',
                $verified
                    ? 'PWD classification verified successfully.'
                    : 'PWD classification rejected. The rejection remarks were saved.'
            );
        }

        if (empty($resident->student_proof)) {
            return back()->with(
                'error',
                'Student proof is required before Student verification.'
            );
        }

        DB::statement(
            'UPDATE residents
             SET student_status = ?,
                 is_student = '.($verified ? 'TRUE' : 'FALSE').',
                 student_verified_at = ?,
                 student_verified_by = ?,
                 student_rejection_reason = ?,
                 updated_at = ?
             WHERE id = ?',
            [
                $decision,
                $verified ? $now : null,
                $verified ? $adminId : null,
                $verified ? null : trim((string) $validated['remarks']),
                $now,
                $resident->id,
            ]
        );

        $this->log(
            $resident,
            $verified
                ? 'Student Classification Verified'
                : 'Student Classification Rejected'
        );

        return back()->with(
            'success',
            $verified
                ? 'Student classification verified successfully.'
                : 'Student classification rejected. The rejection remarks were saved.'
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
            AdminActivityLog::create([
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
            'Valid ID for '.$resident->full_name.' has been approved. '.$this->formatValidIdNotificationSummary($notificationResult)
        );
    }

    public function rejectValidId(Request $request, int $id)
    {
        $validated = $request->validate([
            'valid_id_rejection_reason' => ['required', 'string', 'max:500'],
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
            AdminActivityLog::create([
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
            'Valid ID for '.$resident->full_name.' has been rejected. '.$this->formatValidIdNotificationSummary($notificationResult)
        );
    }

    private function notifyResidentValidIdRejected(Residents $resident, string $remarks): array
    {
        $result = [
            'email' => 'skipped',

        ];

        $residentName = trim((string) ($resident->full_name ?? ($resident->firstname.' '.$resident->lastname))) ?: 'Resident';
        $remarks = trim($remarks) !== '' ? trim($remarks) : 'No remarks provided.';

        $message = "Hello {$residentName}, your valid ID verification was rejected. Remarks: {$remarks} Please log in to your account and upload a clearer front and back copy of your valid ID. - Barangay Hulong Duhat";

        if (! empty($resident->email)) {
            try {
                app(EmailDeliveryService::class)->sendText($resident->email, 'Valid ID Verification Rejected', $message);

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

        $residentName = trim((string) ($resident->full_name ?? ($resident->firstname.' '.$resident->lastname))) ?: 'Resident';

        $message = "Hello {$residentName}, your valid ID has been verified and approved. Your resident account is now fully verified. - Barangay Hulong Duhat";

        try {
            app(EmailDeliveryService::class)->sendText($resident->email, 'Valid ID Verified', $message);

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

        if ($sent && ! $failed) {
            return 'Notification sent via '.implode(' and ', $sent).'.';
        }

        if ($sent && $failed) {
            return 'Notification sent via '.implode(' and ', $sent).', but '.implode(' and ', $failed).' failed.';
        }

        if ($failed) {
            return 'The ID was rejected, but '.implode(' and ', $failed).' notification failed. Check mail/Semaphore settings.';
        }

        return 'No notification was sent because the resident has no email/contact or SMS settings are missing.';
    }

    private function formatPhilippineMobileNumber(string $contact): ?string
    {
        $digits = preg_replace('/\D+/', '', $contact);

        if (preg_match('/^09\d{9}$/', $digits)) {
            return '63'.substr($digits, 1);
        }

        if (preg_match('/^639\d{9}$/', $digits)) {
            return $digits;
        }

        return null;
    }

    private function logNotification(string $channel, string $recipient, string $status, string $message, int $residentId, ?string $error = null): void
    {
        if (! class_exists(NotificationLog::class)) {
            return;
        }

        try {
            NotificationLog::create([
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

    private function storeResidentClassificationFile(Request $request, string $field, string $classification): string
    {
        $file = $request->file($field);

        if (! $file || ! $file->isValid()) {
            throw new \RuntimeException('The uploaded classification proof for '.$field.' is invalid.');
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = $classification.'_'.$field.'_'.now('Asia/Manila')->format('Ymd_His').'_'.uniqid('', true).'.'.$extension;

        return app(SupabaseStorageService::class)->storePrivateFileWithName(
            $file,
            'evidences/resident_classifications',
            $filename
        );
    }

    private function storeResidentValidIdFile(Request $request, string $field): string
    {
        $file = $request->file($field);

        if (! $file || ! $file->isValid()) {
            throw new \RuntimeException('The uploaded file for '.$field.' is invalid.');
        }

        $uploadDirectory = 'private/valid_id/residents';

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = $field.'_'.now('Asia/Manila')->format('Ymd_His').'_'.uniqid('', true).'.'.$extension;

        return app(SupabaseStorageService::class)->storePrivateFileWithName(
            $file,
            'valid_id/residents',
            $filename
        );
    }

    private function deleteResidentValidIdFile(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        app(SupabaseStorageService::class)->delete($path);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:residents,id',
        ]);

        $residents = Residents::whereIn('id', $request->ids)->get();

        foreach ($residents as $resident) {
            $resident->delete();
            $this->log($resident, 'Bulk Archived');
        }

        if (auth('admin')->check()) {
            AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'BULK_ARCHIVE',
                'module' => 'Residents',
                'details' => [
                    'ids' => $request->ids,
                    'count' => count($request->ids),
                    'archived_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return back()->with('success', 'Selected residents archived.');
    }

    public function export(Request $request)
    {
        return app(AdminRecordExport::class)->download($request, 'residents');
    }

    private function log(Residents $resident, string $action): void
    {
        ResidentActivity::create([
            'residents_id' => $resident->id,
            'action' => $action,
            'description' => $action.' by '.(auth('admin')->user()?->full_name),
        ]);
    }
}
