<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use App\Models\Residents;
use App\Models\ResidencyApplication;
use App\Models\IndigencyApplication;
use App\Models\BarangayClearance;
use App\Models\BlotterReport;
use App\Models\ResidentProfileCorrectionConcern;
use App\Services\SupabaseStorageService;
use App\Support\UploadedImageComparator;

class ProfileController extends Controller
{
    public function show()
    {
        
        $resident = $this->currentResident();
        $userId = $resident->id;

        $residencyCount = ResidencyApplication::where('user_id', $userId)->count();

        $indigencyCount = IndigencyApplication::where('user_id', $userId)->count();

        $clearanceCount = BarangayClearance::where('user_id', $userId)->count();

        $blotterCount = BlotterReport::where('user_id', $userId)->count();

        $totalRequests  = $residencyCount + $indigencyCount + $clearanceCount + $blotterCount;

        $residencyRecords = ResidencyApplication::where('user_id', $userId)
            ->select('reference_number', 'status', 'created_at', DB::raw("'Certificate of Residency' as type"));

        $indigencyRecords = IndigencyApplication::where('user_id', $userId)
            ->select('reference_number', 'status', 'created_at', DB::raw("'Certificate of Indigency' as type"));

        $clearanceRecords = BarangayClearance::where('user_id', $userId)
            ->select('reference_number', 'status', 'created_at', DB::raw("'Barangay Clearance' as type"));

        $blotterRecords = BlotterReport::where('user_id', $userId)
            ->select('reference_number', 'status', 'created_at', DB::raw("'Incident Report' as type"));

        $recentRequests = $residencyRecords
            ->union($indigencyRecords)
            ->union($clearanceRecords)
            ->union($blotterRecords)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('barangay_system.profile', compact(
            'resident', 'totalRequests',
            'residencyCount', 'indigencyCount', 'clearanceCount', 'blotterCount',
            'recentRequests'
        ));
    }

    public function updateInfo(Request $request)
    {

        $resident = $this->currentResident();

        if (is_string($request->input('contact')) && preg_match('/^9\d{9}$/', $request->input('contact'))) {
            $request->merge(['contact' => '0' . $request->input('contact')]);
        }

        $validated = $request->validate([
            'contact' => ['required', 'string', 'regex:/^09\d{9}$/', Rule::unique('residents', 'contact')->ignore($resident->id)],
        ], [
            'contact.regex' => 'Please enter a valid Philippine mobile number. Example: 9123456789.',
        ]);

        $contact = $validated['contact'];

        if (strlen($contact) === 10 && ! str_starts_with($contact, '0')) {
            $contact = '0' . $contact;
        }

        if ($contact === $resident->contact && $resident->phone_verified) {
            return back()->with('open_tab', 'info')->with('success_info', 'This contact number is already verified.');
        }

        $otp = random_int(100000, 999999);
        $recipient = clone $resident;
        $recipient->contact = $contact;
        if (! app(\App\Services\OtpSmsService::class)->sendOtp($recipient, $otp, 'profile_contact')) {
            return back()->withErrors(['contact' => 'We could not send the verification code. Your contact number has not changed. Please try again later.'])->with('open_tab', 'info');
        }
        $request->session()->put('contact_change', [
            'resident_id' => $resident->id, 'contact' => $contact, 'hash' => Hash::make((string) $otp),
            'expires_at' => now()->addMinutes(5)->timestamp, 'attempts' => 0,
        ]);

        return redirect()
            ->route('profile')
            ->with('open_tab', 'info')
            ->with('success_info', 'Enter the code sent to your new number. Your current number stays active until verification.');
    }

    public function verifyContact(Request $request)
    {
        $resident = $this->currentResident();
        $request->validate(['contact_otp' => ['required', 'digits:6']]);
        $challenge = $request->session()->get('contact_change');
        if (! $challenge || $challenge['resident_id'] !== $resident->id || $challenge['expires_at'] < now()->timestamp || $challenge['attempts'] >= 5) {
            $request->session()->forget('contact_change');
            return back()->withErrors(['contact_otp' => 'This code has expired. Request a new code using the contact form.'])->with('open_tab', 'info');
        }
        $request->session()->put('contact_change.attempts', $challenge['attempts'] + 1);
        if (! Hash::check((string) $request->input('contact_otp'), $challenge['hash'])) {
            return back()->withErrors(['contact_otp' => 'That code does not match. Please check the SMS and try again.'])->with('open_tab', 'info');
        }
        validator(['contact' => $challenge['contact']], ['contact' => [Rule::unique('residents', 'contact')->ignore($resident->id)]])->validate();
        $resident->update([
            'contact' => $challenge['contact'], 'phone_verified' => true,
            'phone_otp' => null, 'phone_otp_expires_at' => null,
        ]);
        $request->session()->forget('contact_change');

        return redirect()->route('profile')->with('open_tab', 'info')->with('success_info', 'Your new contact number is verified and saved.');
    }

    public function reportIncorrectProfile(Request $request)
    {
        
        $resident = $this->currentResident();

        return DB::transaction(function () use ($request, $resident) {
            
            $resident = Residents::whereKey($resident->id)
                ->lockForUpdate()
                ->firstOrFail();

            $existingPending = ResidentProfileCorrectionConcern::query()
                ->where('resident_id', $resident->id)
                ->whereIn('status', ['pending', 'reviewed'])
                ->latest()
                ->first();

            if ($existingPending) {
                return redirect()
                    ->route('profile')
                    ->with('open_tab', 'info')
                    ->with('warning', 'You already have a pending profile correction concern. Please wait for barangay review before submitting another correction.')
                    ->with('pending_correction_id', $existingPending->id);
            }

            $latestConcern = ResidentProfileCorrectionConcern::query()
                ->where('resident_id', $resident->id)
                ->latest()
                ->first();

            if ($latestConcern && $latestConcern->created_at->gt(now()->subMinutes(10))) {
                return redirect()
                    ->route('profile')
                    ->with('open_tab', 'info')
                    ->with('warning', 'Please wait at least 10 minutes before submitting another profile correction concern.');
            }

            $todayCount = ResidentProfileCorrectionConcern::query()
                ->where('resident_id', $resident->id)
                ->whereDate('created_at', today())
                ->count();

            if ($todayCount >= 3) {
                return redirect()
                    ->route('profile')
                    ->with('open_tab', 'info')
                    ->with('warning', 'You have reached the daily limit for profile correction concerns. Please try again tomorrow or contact the barangay office.');
            }

            $validated = $request->validate([
                'service' => ['nullable', 'string', 'max:100'],
                'field_name' => [
                    'required',
                    'string',
                    Rule::in([
                        'firstname',
                        'middlename',
                        'lastname',
                        'suffix',
                        'birthdate',
                        'address',
                        'gender',
                        'civil_status',
                        'place_of_birth',
                    ]),
                ],
                'current_value' => ['nullable', 'string', 'max:2000'],
                'correct_value' => ['required', 'string', 'min:2', 'max:2000'],
                'message' => ['nullable', 'string', 'max:3000'],
            ], [
                'field_name.required' => 'Please choose which profile field needs correction.',
                'field_name.in' => 'The selected profile field is not allowed for correction.',
                'correct_value.required' => 'Please enter the correct value.',
                'correct_value.min' => 'Correct value is too short.',
                'correct_value.max' => 'Correct value is too long.',
                'message.max' => 'Message is too long.',
            ]);

            $allowedCurrentValues = [
                'firstname' => $resident->firstname,
                'middlename' => $resident->middlename,
                'lastname' => $resident->lastname,
                'suffix' => $resident->suffix,
                'birthdate' => $resident->birthdate,
                'address' => $resident->address,
                'gender' => $resident->gender ?? null,
                'civil_status' => $resident->civil_status ?? null,
                'place_of_birth' => $resident->place_of_birth ?? null,
            ];

            $fieldName = $validated['field_name'];
            $currentValue = $allowedCurrentValues[$fieldName] ?? null;

            if ($currentValue instanceof \DateTimeInterface) {
                $currentValue = $currentValue->format('Y-m-d');
            }

            if (strtolower(trim((string) $currentValue)) === strtolower(trim((string) $validated['correct_value']))) {
                return redirect()
                    ->route('profile')
                    ->with('open_tab', 'info')
                    ->with('warning', 'The correct value you entered is the same as your current profile value.');
            }

            ResidentProfileCorrectionConcern::create([
                'resident_id' => $resident->id,
                'service' => $validated['service'] ?? 'Profile',
                'field_name' => $fieldName,
                'current_value' => (string) ($currentValue ?? ''),
                'correct_value' => trim((string) $validated['correct_value']),
                'message' => $validated['message'] ?? null,
                'status' => 'pending',
            ]);

            return redirect()
                ->route('profile')
                ->with('open_tab', 'info')
                ->with('success', 'Profile correction concern submitted successfully. Request forms are temporarily locked until barangay review is completed.');
        });
    }

    public function updateAccount(Request $request)
    {
        
        $resident = $this->currentResident();

        $validated = $request->validate([
            'username' => [
                'required', 'string', 'min:4', 'max:50',
                'regex:/^[a-zA-Z0-9_.-]+$/',
                new \App\Rules\CaseInsensitiveUnique('residents', 'username', $resident->id),
            ],
            'email' => [
                'required', 'email', 'max:255',
                new \App\Rules\CaseInsensitiveUnique('residents', 'email', $resident->id),
            ],
        ], [
            'username.regex'  => 'Username may only contain letters, numbers, underscores, dots, and hyphens.',
            'username.min'    => 'Username must be at least 4 characters.',
            'username.unique' => 'This username is already taken.',
            'email.unique'    => 'This email is already registered to another account.',
        ]);

        $emailChanged = mb_strtolower(trim($validated['email'])) !== mb_strtolower($resident->email);

        $resident->username = $validated['username'];
        $resident->email = $validated['email'];

        if ($emailChanged) {
            $resident->email_verified_at = null;
        }

        $resident->save();

        if ($emailChanged) {
            $emailThrottleKey = 'verify-email-sent:' . $resident->id . ':' . $resident->email;

            if (!Cache::has($emailThrottleKey)) {
                try {
                    $resident->sendEmailVerificationNotification();
                } catch (\Throwable $e) {
                    report($e);
                    return redirect()->route('verification.notice')->with('error', 'Your email was updated, but we could not send the verification link. Use Resend verification link to try again.');
                }
                Cache::put($emailThrottleKey, true, now()->addMinutes(2));
            }

            return redirect()->route('verification.notice')
                ->with('success', 'Email updated. Please verify your new email address.');
        }

        return redirect()->route('profile')->with('success_account', 'Account settings updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        
        $resident = $this->currentResident();

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', 'confirmed', 'regex:/^\S+$/', PasswordRules::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'new_password.confirmed' => 'The new password confirmation does not match.',
            'new_password.regex'     => 'New password cannot contain spaces.',
            'new_password.*'         => 'New password must be at least 8 characters and include uppercase, lowercase, number, and special character.',
        ]);

        if (!Hash::check($request->current_password, $resident->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])
                         ->with('open_tab', 'account');
        }

        $resident->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('profile')->with('success_password', 'Password changed successfully.');
    }

    public function updatePhoto(Request $request)
    {
        if (!$request->hasFile('profile_photo')) {
            return redirect()->route('profile')
                ->withErrors(['profile_photo' => 'Please choose an image file to upload.'])
                ->with('open_tab', 'info');
        }

        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'profile_photo.image' => 'The file must be an image.',
            'profile_photo.mimes' => 'Only JPG, PNG, and WebP images are allowed.',
            'profile_photo.max'   => 'Photo must not exceed 5MB.',
        ]);

        $resident = $this->currentResident();

        $oldProfilePhoto = $resident->profile_photo;

        try {
            $file = $request->file('profile_photo');
            $newProfilePhoto = app(SupabaseStorageService::class)->storePrivateUpload(
                $file,
                'profile_photos',
                'profile_' . $resident->id
            );

            $resident->update(['profile_photo' => $newProfilePhoto]);
            $this->deletePublicFile($oldProfilePhoto);
        } catch (\Throwable $e) {
            return redirect()->route('profile')
                ->withErrors(['profile_photo' => 'Photo upload failed. Please try again.'])
                ->with('open_tab', 'info');
        }

        return redirect()->route('profile')->with('success_photo', 'Profile photo updated successfully.');
    }

    public function removePhoto()
    {
        
        $resident = $this->currentResident();

        $oldProfilePhoto = $resident->profile_photo;
        $resident->update(['profile_photo' => null]);
        $this->deletePublicFile($oldProfilePhoto);

        return redirect()->route('profile')->with('success_photo', 'Profile photo removed.');
    }

    public function updateId(Request $request)
    {
        $request->validate([
            'valid_id' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'valid_id_back' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ], [
            'valid_id.required' => 'Please scan or upload the front side of your valid ID.',
            'valid_id_back.required' => 'Please scan or upload the back side of your valid ID.',
            'valid_id.mimes' => 'Front ID must be JPG, PNG, WebP, or PDF.',
            'valid_id_back.mimes' => 'Back ID must be JPG, PNG, WebP, or PDF.',
            'valid_id.max' => 'Front ID must not exceed 5MB.',
            'valid_id_back.max' => 'Back ID must not exceed 5MB.',
        ]);

        $resident = $this->currentResident();

        $frontFile = $request->file('valid_id');
        $backFile = $request->file('valid_id_back');

        if (!$frontFile instanceof UploadedFile || !$backFile instanceof UploadedFile) {
            return redirect()->route('profile')
                ->withErrors(['valid_id' => 'Please scan both the front and back side of your valid ID.'])
                ->with('open_tab', 'valid-id');
        }

        if (UploadedImageComparator::areSameOrNearDuplicate($frontFile, $backFile)) {
            return redirect()->route('profile')
                ->withErrors(['valid_id_back' => 'The back ID appears to be the same picture as the front. Please flip the ID and capture the real back side.'])
                ->with('open_tab', 'valid-id');
        }

        try {
            $oldFrontPath = $resident->valid_id;
            $oldBackPath = $resident->valid_id_back ?? null;
            $frontPath = $this->storeResidentValidIdFile($frontFile, $resident, 'front');
            $backPath = $this->storeResidentValidIdFile($backFile, $resident, 'back');

            DB::statement(
                'UPDATE residents
                 SET valid_id = ?,
                     valid_id_back = ?,
                     valid_id_verified = FALSE,
                     valid_id_status = ?,
                     valid_id_verified_at = NULL,
                     valid_id_verified_by = NULL,
                     valid_id_rejection_reason = NULL,
                     updated_at = ?
                 WHERE id = ?',
                [
                    $frontPath,
                    $backPath,
                    'for_verification',
                    now('Asia/Manila'),
                    $resident->id,
                ]
            );

            $resident->valid_id = $frontPath;
            $resident->valid_id_back = $backPath;
            $resident->valid_id_verified = false;
            $resident->valid_id_status = 'for_verification';
            $resident->valid_id_verified_at = null;
            $resident->valid_id_verified_by = null;
            $resident->valid_id_rejection_reason = null;

            $this->deletePublicFile($oldFrontPath);
            $this->deletePublicFile($oldBackPath);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('profile')
                ->withErrors(['valid_id' => 'Valid ID upload failed. Please try again.'])
                ->with('open_tab', 'valid-id');
        }

        return redirect()->route('profile')
            ->with('open_tab', 'valid-id')
            ->with('success_photo', 'Valid ID front and back uploaded successfully. It will be reviewed by barangay staff.');
    }

    public function updateClassification(Request $request)
    {
        $resident = $this->currentResident();

        $validated = $request->validate([
            'classification' => ['required', Rule::in(['pwd', 'student'])],
            'pwd_id_front' => ['required_if:classification,pwd', 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'pwd_id_back' => ['required_if:classification,pwd', 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'student_proof' => ['required_if:classification,student', 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'pwd_id_front.required_if' => 'Please upload the front side of your PWD ID card.',
            'pwd_id_back.required_if' => 'Please upload the back side of your PWD ID card.',
            'student_proof.required_if' => 'Please upload a current school ID, registration form, or certificate of enrollment.',
        ]);

        $classification = $validated['classification'];

        if ($classification === 'pwd' && in_array((string) $resident->pwd_status, ['pending', 'verified'], true)) {
            return redirect()->route('profile')
                ->with('open_tab', 'classification')
                ->with('warning', $resident->pwd_status === 'verified'
                    ? 'Your PWD classification is already verified.'
                    : 'Your PWD evidence is already pending review.');
        }

        if ($classification === 'student' && in_array((string) $resident->student_status, ['pending', 'verified'], true)) {
            return redirect()->route('profile')
                ->with('open_tab', 'classification')
                ->with('warning', $resident->student_status === 'verified'
                    ? 'Your Student classification is already verified.'
                    : 'Your Student evidence is already pending review.');
        }

        if ($classification === 'pwd') {
            $front = $request->file('pwd_id_front');
            $back = $request->file('pwd_id_back');

            if (UploadedImageComparator::areSameOrNearDuplicate($front, $back)) {
                return redirect()->route('profile')
                    ->withErrors(['pwd_id_back' => 'The PWD ID back appears to be the same picture as the front. Please upload the actual back side.'])
                    ->with('open_tab', 'classification');
            }

            $newPaths = [];

            try {
                $frontPath = $this->storeResidentClassificationFile($front, $resident, 'pwd_front');
                $newPaths[] = $frontPath;
                $backPath = $this->storeResidentClassificationFile($back, $resident, 'pwd_back');
                $newPaths[] = $backPath;

                $oldFront = $resident->pwd_id_front;
                $oldBack = $resident->pwd_id_back;

                DB::statement(
                    'UPDATE residents
                     SET pwd_id_front = ?,
                         pwd_id_back = ?,
                         pwd_status = ?,
                         is_pwd = FALSE,
                         pwd_verified_at = NULL,
                         pwd_verified_by = NULL,
                         pwd_rejection_reason = NULL,
                         updated_at = ?
                     WHERE id = ?',
                    [
                        $frontPath,
                        $backPath,
                        'pending',
                        now('Asia/Manila'),
                        $resident->id,
                    ]
                );

                $resident->pwd_id_front = $frontPath;
                $resident->pwd_id_back = $backPath;
                $resident->pwd_status = 'pending';
                $resident->is_pwd = false;
                $resident->pwd_verified_at = null;
                $resident->pwd_verified_by = null;
                $resident->pwd_rejection_reason = null;

                $this->deletePublicFile($oldFront);
                $this->deletePublicFile($oldBack);
            } catch (\Throwable $e) {
                foreach ($newPaths as $path) {
                    $this->deletePublicFile($path);
                }
                report($e);

                return redirect()->route('profile')
                    ->withErrors(['pwd_id_front' => 'PWD ID upload failed. Please try again.'])
                    ->with('open_tab', 'classification');
            }

            return redirect()->route('profile')
                ->with('open_tab', 'classification')
                ->with('success', 'PWD ID submitted. Your PWD classification is pending barangay verification.');
        }

        $proof = $request->file('student_proof');
        $newPath = null;

        try {
            $newPath = $this->storeResidentClassificationFile($proof, $resident, 'student');
            $oldProof = $resident->student_proof;

            DB::statement(
                'UPDATE residents
                 SET student_proof = ?,
                     student_status = ?,
                     is_student = FALSE,
                     student_verified_at = NULL,
                     student_verified_by = NULL,
                     student_rejection_reason = NULL,
                     updated_at = ?
                 WHERE id = ?',
                [
                    $newPath,
                    'pending',
                    now('Asia/Manila'),
                    $resident->id,
                ]
            );

            $resident->student_proof = $newPath;
            $resident->student_status = 'pending';
            $resident->is_student = false;
            $resident->student_verified_at = null;
            $resident->student_verified_by = null;
            $resident->student_rejection_reason = null;

            $this->deletePublicFile($oldProof);
        } catch (\Throwable $e) {
            if ($newPath) {
                $this->deletePublicFile($newPath);
            }
            report($e);

            return redirect()->route('profile')
                ->withErrors(['student_proof' => 'Student proof upload failed. Please try again.'])
                ->with('open_tab', 'classification');
        }

        return redirect()->route('profile')
            ->with('open_tab', 'classification')
            ->with('success', 'Student proof submitted. Your Student classification is pending barangay verification.');
    }

    private function currentResident(): Residents
    {
        $user = Auth::user();

        if (! $user instanceof Residents) {
            abort(401, 'Resident authentication is required.');
        }

        return $user;
    }

    private function storeResidentClassificationFile(?UploadedFile $file, Residents $resident, string $type): string
    {
        if (! $file || ! $file->isValid()) {
            throw new \RuntimeException('The uploaded classification proof is invalid.');
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = $type . '_' . now()->format('Ymd_His') . '_' . $resident->id . '_' . Str::random(10) . '.' . $extension;

        return app(SupabaseStorageService::class)->storePrivateFileWithName(
            $file,
            'evidences/resident_classifications',
            $filename
        );
    }

    private function storeResidentValidIdFile(UploadedFile $file, Residents $resident, string $side): string
    {
        $uploadDir = 'private/valid_id/residents';

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = 'valid_id_' . $side . '_' . now()->format('Ymd_His') . '_' . $resident->id . '_' . Str::random(10) . '.' . $extension;

        return app(SupabaseStorageService::class)->storePrivateFileWithName(
            $file,
            'valid_id/residents',
            $filename
        );
    }

    private function deletePublicFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (str_contains($path, '..')) {
            return;
        }

        app(SupabaseStorageService::class)->delete($path);
    }

}
