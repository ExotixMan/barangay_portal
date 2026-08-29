<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class ProfileController extends Controller
{
    public function show()
    {
        
        $resident = Auth::user();
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

        $resident = Auth::user();

        $validated = $request->validate([
            'contact' => ['required', 'string', 'regex:/^(09\d{9}|9\d{9})$/'],
        ], [
            'contact.regex' => 'Please enter a valid Philippine mobile number. Example: 9123456789.',
        ]);

        $contact = $validated['contact'];

        if (strlen($contact) === 10 && ! str_starts_with($contact, '0')) {
            $contact = '0' . $contact;
        }

        $resident->update([
            'contact' => $contact,
        ]);

        return redirect()
            ->route('profile')
            ->with('open_tab', 'info')
            ->with('success_info', 'Contact number updated successfully.');
    }

    public function reportIncorrectProfile(Request $request)
    {
        
        $resident = Auth::user();

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
                        'birth_place',
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
                'birth_place' => $resident->birth_place ?? null,
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
        
        $resident = Auth::user();

        $validated = $request->validate([
            'username' => [
                'required', 'string', 'min:4', 'max:50',
                'regex:/^[a-zA-Z0-9_.-]+$/',
                Rule::unique('residents', 'username')->ignore($resident->id),
            ],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('residents', 'email')->ignore($resident->id),
            ],
        ], [
            'username.regex'  => 'Username may only contain letters, numbers, underscores, dots, and hyphens.',
            'username.min'    => 'Username must be at least 4 characters.',
            'username.unique' => 'This username is already taken.',
            'email.unique'    => 'This email is already registered to another account.',
        ]);

        $emailChanged = $validated['email'] !== $resident->email;
        $resident->fill($validated);

        if ($emailChanged) {
            $resident->email_verified_at = null;
        }

        $resident->save();

        if ($emailChanged) {
            $emailThrottleKey = 'verify-email-sent:' . $resident->id . ':' . $resident->email;

            if (!Cache::has($emailThrottleKey)) {
                $resident->sendEmailVerificationNotification();
                Cache::put($emailThrottleKey, true, now()->addMinutes(2));
            }

            return redirect()->route('verification.notice')
                ->with('success', 'Email updated. Please verify your new email address.');
        }

        return redirect()->route('profile')->with('success_account', 'Account settings updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        
        $resident = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'new_password.confirmed' => 'The new password confirmation does not match.',
            'new_password.min'       => 'New password must be at least 8 characters.',
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

        $resident = Auth::user();

        if ($resident->profile_photo && file_exists(public_path($resident->profile_photo))) {
            unlink(public_path($resident->profile_photo));
        }

        try {
            $uploadDir = public_path('uploads/profile_photos');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $file      = $request->file('profile_photo');
            $extension = strtolower($file->getClientOriginalExtension());
            $filename  = 'profile_' . $resident->id . '_' . time() . '.' . $extension;
            $file->move($uploadDir, $filename);

            $resident->update(['profile_photo' => 'uploads/profile_photos/' . $filename]);
        } catch (\Throwable $e) {
            return redirect()->route('profile')
                ->withErrors(['profile_photo' => 'Photo upload failed. Please try again.'])
                ->with('open_tab', 'info');
        }

        return redirect()->route('profile')->with('success_photo', 'Profile photo updated successfully.');
    }

    public function removePhoto()
    {
        
        $resident = Auth::user();

        if ($resident->profile_photo && file_exists(public_path($resident->profile_photo))) {
            unlink(public_path($resident->profile_photo));
        }

        $resident->update(['profile_photo' => null]);

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

        $resident = Auth::user();

        $frontFile = $request->file('valid_id');
        $backFile = $request->file('valid_id_back');

        if (!$frontFile instanceof UploadedFile || !$backFile instanceof UploadedFile) {
            return redirect()->route('profile')
                ->withErrors(['valid_id' => 'Please scan both the front and back side of your valid ID.'])
                ->with('open_tab', 'valid-id');
        }

        if ($this->uploadedFilesAreIdentical($frontFile, $backFile)) {
            return redirect()->route('profile')
                ->withErrors(['valid_id_back' => 'The back ID image appears to be the same as the front. Please flip the ID and scan the back side.'])
                ->with('open_tab', 'valid-id');
        }

        try {
            $frontPath = $this->storeResidentValidIdFile($frontFile, $resident, 'front');
            $backPath = $this->storeResidentValidIdFile($backFile, $resident, 'back');

            $this->deletePublicFile($resident->valid_id);
            $this->deletePublicFile($resident->valid_id_back ?? null);

            $resident->forceFill([
                'valid_id' => $frontPath,
                'valid_id_back' => $backPath,
                'valid_id_verified' => DB::raw('FALSE'),
                'valid_id_status' => 'for_verification',
                'valid_id_verified_at' => null,
                'valid_id_verified_by' => null,
                'valid_id_rejection_reason' => null,
            ])->save();
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

    private function storeResidentValidIdFile(UploadedFile $file, Residents $resident, string $side): string
    {
        $uploadDir = public_path('uploads/valid_id/residents');

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = 'valid_id_' . $side . '_' . now()->format('Ymd_His') . '_' . $resident->id . '_' . Str::random(10) . '.' . $extension;

        $file->move($uploadDir, $filename);

        return 'uploads/valid_id/residents/' . $filename;
    }

    private function deletePublicFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        $cleanPath = ltrim(str_replace(['../', '..\\'], '', $path), '/\\');
        $fullPath = public_path($cleanPath);

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function uploadedFilesAreIdentical(UploadedFile $frontFile, UploadedFile $backFile): bool
    {
        $frontRealPath = $frontFile->getRealPath();
        $backRealPath = $backFile->getRealPath();

        if (!$frontRealPath || !$backRealPath || !is_file($frontRealPath) || !is_file($backRealPath)) {
            return false;
        }

        return hash_file('sha256', $frontRealPath) === hash_file('sha256', $backRealPath);
    }
}
