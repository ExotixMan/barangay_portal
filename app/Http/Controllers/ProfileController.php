<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Residents;
use App\Models\ResidencyApplication;
use App\Models\IndigencyApplication;
use App\Models\BarangayClearance;
use App\Models\BlotterReport;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var Residents $resident */
        $resident = Auth::user();

        $residencyCount = ResidencyApplication::where('email', $resident->email)->count();
        $indigencyCount = IndigencyApplication::where('email', $resident->email)->count();
        $clearanceCount = BarangayClearance::where('email', $resident->email)->count();
        $blotterCount   = BlotterReport::where('complainant_email', $resident->email)->count();
        $totalRequests  = $residencyCount + $indigencyCount + $clearanceCount + $blotterCount;

        $residencyRecords = ResidencyApplication::where('email', $resident->email)
            ->select('reference_number', 'status', 'created_at', DB::raw("'Certificate of Residency' as type"));
        $indigencyRecords = IndigencyApplication::where('email', $resident->email)
            ->select('reference_number', 'status', 'created_at', DB::raw("'Certificate of Indigency' as type"));
        $clearanceRecords = BarangayClearance::where('email', $resident->email)
            ->select('reference_number', 'status', 'created_at', DB::raw("'Barangay Clearance' as type"));
        $blotterRecords = BlotterReport::where('complainant_email', $resident->email)
            ->select('reference_number', 'status', 'created_at', DB::raw("'Blotter Report' as type"));

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
        /** @var Residents $resident */
        $resident = Auth::user();

        $validated = $request->validate([
            'firstname'  => ['required', 'string', 'max:255', 'regex:/^[\pL\s\'\.,-]+$/u'],
            'middlename' => ['nullable', 'string', 'max:255', 'regex:/^[\pL\s\'\.,-]+$/u'],
            'lastname'   => ['required', 'string', 'max:255', 'regex:/^[\pL\s\'\.,-]+$/u'],
            'suffix'     => ['nullable', 'string', 'max:10', 'in:,Jr.,Sr.,II,III,IV,V'],
            'birthdate'  => ['required', 'date', 'before:18 years ago'],
            'contact'    => ['required', 'string', 'regex:/^(09\d{9}|9\d{9})$/'],
            'address'    => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'firstname.regex'  => 'First name may only contain letters, spaces, and basic punctuation.',
            'middlename.regex' => 'Middle name may only contain letters, spaces, and basic punctuation.',
            'lastname.regex'   => 'Last name may only contain letters, spaces, and basic punctuation.',
            'suffix.in'        => 'Please select a valid suffix.',
            'contact.regex'    => 'Please enter a valid Philippine mobile number (09XXXXXXXXX).',
            'birthdate.before' => 'You must be at least 18 years old.',
        ]);

        if (($validated['suffix'] ?? '') === '') {
            $validated['suffix'] = null;
        }

        // Normalize contact: store always as 09XXXXXXXXX
        if (isset($validated['contact']) && strlen($validated['contact']) === 10 && !str_starts_with($validated['contact'], '0')) {
            $validated['contact'] = '0' . $validated['contact'];
        }

        $resident->update($validated);

        return redirect()->route('profile')->with('success_info', 'Personal information updated successfully.');
    }

    public function updateAccount(Request $request)
    {
        /** @var Residents $resident */
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
        $resident->update($validated);

        if ($emailChanged) {
            $resident->update(['email_verified_at' => null]);
            $resident->sendEmailVerificationNotification();
            return redirect()->route('verification.notice')
                ->with('success', 'Email updated. Please verify your new email address.');
        }

        return redirect()->route('profile')->with('success_account', 'Account settings updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        /** @var Residents $resident */
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
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'profile_photo.image' => 'The file must be an image.',
            'profile_photo.mimes' => 'Only JPG, PNG, and WebP images are allowed.',
            'profile_photo.max'   => 'Photo must not exceed 2MB.',
        ]);

        /** @var Residents $resident */
        $resident = Auth::user();

        if ($resident->profile_photo && file_exists(public_path($resident->profile_photo))) {
            unlink(public_path($resident->profile_photo));
        }

        $file     = $request->file('profile_photo');
        $filename = 'profile_' . $resident->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/profile_photos'), $filename);
        $resident->update(['profile_photo' => 'uploads/profile_photos/' . $filename]);

        return redirect()->route('profile')->with('success_photo', 'Profile photo updated successfully.');
    }

    public function removePhoto()
    {
        /** @var Residents $resident */
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
            'valid_id' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'valid_id.mimes' => 'Only JPG, PNG, and PDF files are accepted.',
            'valid_id.max'   => 'File size must not exceed 5MB.',
        ]);

        /** @var Residents $resident */
        $resident = Auth::user();

        if ($resident->valid_id && file_exists(public_path($resident->valid_id))) {
            unlink(public_path($resident->valid_id));
        }

        $file     = $request->file('valid_id');
        $filename = 'id_' . $resident->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/valid_ids'), $filename);

        $resident->update([
            'valid_id'          => 'uploads/valid_ids/' . $filename,
            'valid_id_verified' => false,
        ]);

        return redirect()->route('profile')->with('open_tab', 'valid-id')
            ->with('success_photo', 'Valid ID uploaded successfully. It will be reviewed by barangay staff.');
    }

    // ---- keep the old Breeze stubs below so wweb.php routes don't break ----
    public function edit(Request $request)
    {
        return redirect()->route('profile');
    }

    public function update(Request $request)
    {
        return redirect()->route('profile');
    }

    public function destroy(Request $request)
    {
        return redirect()->route('profile');
    }
}
