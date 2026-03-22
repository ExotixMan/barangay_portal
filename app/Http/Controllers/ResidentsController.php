<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Residents;
use App\Models\Announcement;
use Carbon\Carbon;

class ResidentsController extends Controller
{
    public function showLogin(){
        if (Auth::check()) {
            return redirect()->intended(route('barangay_system.index'));
        }

        return view('barangay_system.login');
    }

    public function index(){
        App::setLocale(Session::get('locale', config('app.locale')));

        $announcements = Announcement::latest() 
        ->take(9)
        ->get();

        return view('barangay_system.index', compact('announcements'));
    }

    public function register_res(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'middlename' => 'nullable|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'lastname' => 'required|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'suffix' => 'nullable|string|max:10|in:None,Jr.,Sr.,II,III,IV,V',
            'email' => 'required|email|unique:residents,email',
            'address' => 'required|string|max:500',
            'birthdate' => 'required|date|before:18 years ago',
            'contact' => 'required|string|max:11|regex:/^09\d{9}$/',
            'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'username' => 'required|string|unique:residents,username|min:4|max:50|regex:/^[a-zA-Z0-9_.-]+$/',
            'password' => 'required|string|confirmed|min:8',
            'terms' => 'accepted',
        ], [
            'firstname.regex' => 'First name may only contain letters, spaces, dots, apostrophes, and hyphens',
            'middlename.regex' => 'Middle name may only contain letters, spaces, dots, apostrophes, and hyphens',
            'lastname.regex' => 'Last name may only contain letters, spaces, dots, apostrophes, and hyphens',
            'suffix.in' => 'Please select a valid suffix',
            'contact.regex' => 'Please enter a valid Philippine mobile number (09XXXXXXXXX)',
            'birthdate.before' => 'You must be at least 18 years old to register',
            'password.min' => 'Password must be at least 8 characters long',
            'username.regex' => 'Username may only contain letters, numbers, underscores, dots, and hyphens (no spaces)',
            'username.min' => 'Username must be at least 4 characters long',
            'terms.accepted' => 'You must agree to the Terms and Conditions and Privacy Policy',
        ]);

        if ($request->hasFile('valid_id')) {
            $file = $request->file('valid_id');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $file->move(public_path('uploads/valid_id/residents'), $filename);
            $data['valid_id'] = 'uploads/valid_id/residents/' . $filename;
        }

        if (isset($data['suffix']) && $data['suffix'] === 'None') {
            $data['suffix'] = null;
        }

        $otp = rand(100000, 999999);

        $data['password'] = Hash::make($data['password']);
        
        $data['phone_otp'] = Hash::make($otp);
        $data['phone_otp_expires_at'] = Carbon::now()->addMinutes(5);
        $data['phone_verified'] = 'false';

        $resident = Residents::create($data);

        $resident->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful. Please verify your email.');
    }

    public function login_res(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Determine if the login input is an email or username
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        // Prepare credentials array
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors(['login' => 'Invalid username/email or password.'])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        $resident = Auth::user();

        if (is_null($resident->email_verified_at)) {
            return redirect()->route('verification.notice');
        }

        return redirect()->intended('/');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->forget('loginId');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'resident_id' => 'required|exists:residents,id'
        ]);

        $resident = Residents::find($request->resident_id);

        if (!$resident) {
            return back()->with('fail', 'Resident not found.');
        }

        if (Carbon::now()->gt($resident->phone_otp_expires_at)) {
            return back()->with('fail', 'OTP expired.');
        }

        if (!Hash::check($request->otp, $resident->phone_otp)) {
            return back()->with('fail', 'Invalid OTP.');
        }

        $resident->update([
            'phone_verified' => true,
            'phone_otp' => null,
            'phone_otp_expires_at' => null
        ]);

        return redirect('login')->with('success', 'Phone verified. You can now login.');
    }
}