<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Residents;
use App\Models\Announcement;
use App\Services\OtpSmsService;
use Carbon\Carbon;

class ResidentsController extends Controller
{
    private const REGISTER_OTP_MAX_ATTEMPTS = 3;

    public function __construct(private OtpSmsService $otpSmsService)
    {
    }

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
        $request->merge([
            'username' => strtolower((string) $request->input('username', '')),
        ]);

        $data = $request->validate([
            'firstname' => 'required|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'middlename' => 'nullable|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'lastname' => 'required|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'suffix' => 'nullable|string|max:10|in:None,Jr.,Sr.,II,III,IV,V',
            'email' => 'required|email|unique:residents,email',
            'address' => 'required|string|max:500',
            'birthdate' => 'required|date|before:18 years ago',
            'contact' => 'required|string|max:11|regex:/^09\d{9}$/|unique:residents,contact',
            'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'username' => 'required|string|unique:residents,username|min:4|max:50|regex:/^[a-zA-Z0-9_.-]+$/',
            'password' => 'required|string|confirmed|min:8|regex:/^\S+$/',
            'terms' => 'accepted',
        ], [
            'firstname.regex' => 'First name may only contain letters, spaces, dots, apostrophes, and hyphens',
            'middlename.regex' => 'Middle name may only contain letters, spaces, dots, apostrophes, and hyphens',
            'lastname.regex' => 'Last name may only contain letters, spaces, dots, apostrophes, and hyphens',
            'suffix.in' => 'Please select a valid suffix',
            'contact.regex' => 'Please enter a valid Philippine mobile number (09XXXXXXXXX)',
            'contact.unique' => 'This contact number is already registered',
            'birthdate.before' => 'You must be at least 18 years old to register',
            'password.min' => 'Password must be at least 8 characters long',
            'password.regex' => 'Password cannot contain spaces',
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

        $otp = random_int(100000, 999999);

        $data['password'] = Hash::make($data['password']);
        $data['phone_otp'] = Hash::make($otp);
        $data['phone_otp_expires_at'] = Carbon::now()->addMinutes(5);
        $data['phone_verified'] = 'false';

        $resident = Residents::create($data);

        // Commented out legacy email verification:
        // Auth::login($resident);
        // $resident->sendEmailVerificationNotification();
        // return redirect()->route('verification.notice')
        //     ->with('success', 'Registration successful. Please verify your email.');

        // Send OTP to mobile and redirect to OTP form
        $this->otpSmsService->sendOtp($resident, $otp, 'register');
        $request->session()->put('resident_id', $resident->id);
        $request->session()->put('otp_purpose', 'register');

        return redirect()->route('otp.form')
            ->with('success', 'Registration successful. Please verify your mobile number with OTP.');
    }

    public function checkUsernameAvailability(Request $request)
    {
        $username = strtolower(trim((string) $request->query('username', '')));

        if ($username === '') {
            return response()->json([
                'available' => false,
                'message' => 'Username is required',
            ], 422);
        }

        if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) {
            return response()->json([
                'available' => false,
                'message' => 'Username may only contain letters, numbers, underscores (_), dots (.), and hyphens (-)',
            ], 422);
        }

        if (strlen($username) < 4) {
            return response()->json([
                'available' => false,
                'message' => 'Username must be at least 4 characters long',
            ], 422);
        }

        $exists = Residents::where('username', $username)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Username is already taken' : 'Username is available',
        ]);
    }

    public function login_res(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim((string) $request->login);
        $loginType = 'username';

        // If input is a mobile number, use contact; else username/email
        $mobileInput = preg_replace('/\D+/', '', $loginInput);
        if (preg_match('/^(09\d{9}|9\d{9})$/', $mobileInput)) {
            $loginType = 'contact';
            $loginInput = str_starts_with($mobileInput, '0') ? $mobileInput : ('0' . $mobileInput);
        } elseif (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $loginType = 'email';
            $loginInput = strtolower($loginInput);
        } else {
            $loginInput = strtolower($loginInput);
        }

        $resident = Residents::where($loginType, $loginInput)->first();

        if (!$resident || !Hash::check((string) $request->password, (string) $resident->password)) {
            return back()
                ->withErrors(['login' => 'Invalid username/mobile number or password.'])
                ->onlyInput('login');
        }

        // Always require OTP on login
        $otp = random_int(100000, 999999);
        $resident->update([
            'phone_otp' => Hash::make($otp),
            'phone_otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);
        $this->otpSmsService->sendOtp($resident, $otp, 'login');
        $request->session()->put('resident_id', $resident->id);
        $request->session()->put('otp_purpose', 'login');
        return redirect()->route('otp.form')
            ->with('success', 'Please verify your mobile number with OTP to continue.');
    }
        /**
         * Resend OTP for login or registration (no countdown)
         */
    public function resendOtp(Request $request)
    {
        $residentId = $request->session()->get('resident_id');
        $otpPurpose = $request->session()->get('otp_purpose', 'login');

        if (!$residentId) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'OTP session expired. Please login again.'], 422);
            }

            return redirect()->route('login')->with('fail', 'OTP session expired. Please login again.');
        }

        $resident = Residents::find($residentId);
        if (!$resident) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Resident not found.'], 404);
            }

            return redirect()->route('login')->with('fail', 'Resident not found.');
        }

        if (!empty($resident->phone_otp) && !empty($resident->phone_otp_expires_at) && Carbon::now()->lt($resident->phone_otp_expires_at)) {
            $retryAfter = Carbon::now()->diffInSeconds($resident->phone_otp_expires_at, false);
            $retryAfter = max(1, (int) $retryAfter);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Please wait before requesting another OTP.',
                    'retry_after' => $retryAfter,
                ], 429);
            }

            return back()->with('fail', 'Please wait before requesting another OTP.');
        }

        $otp = random_int(100000, 999999);
        $resident->update([
            'phone_otp' => Hash::make($otp),
            'phone_otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $this->otpSmsService->sendOtp($resident, $otp, $otpPurpose);

        if ($otpPurpose === 'register') {
            $request->session()->forget('register_otp_attempts_' . $resident->id);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => 'A new OTP has been sent to your mobile number.',
                'retry_after' => 300,
            ]);
        }

        return back()->with('success', 'A new OTP has been sent to your mobile number.');
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
            'resident_id' => 'nullable|exists:residents,id'
        ]);

        $otpPurpose = (string) $request->session()->get('otp_purpose', 'login');

        $residentId = $request->session()->get('resident_id', $request->input('resident_id'));
        if (!$residentId) {
            return redirect()->route('login')->with('fail', 'OTP session expired. Please login again.');
        }

        $resident = Residents::find($residentId);

        if (!$resident) {
            return back()->with('fail', 'Resident not found.');
        }

        if (empty($resident->phone_otp) || empty($resident->phone_otp_expires_at)) {
            if ($otpPurpose === 'register') {
                return $this->cancelPendingRegistration($request, $resident, 'OTP verification failed.');
            }

            return back()->with('fail', 'No active OTP found. Please request a new one.');
        }

        if (Carbon::now()->gt($resident->phone_otp_expires_at)) {
            if ($otpPurpose === 'register') {
                return $this->cancelPendingRegistration($request, $resident, 'OTP expired.');
            }

            $resident->update([
                'phone_otp' => null,
                'phone_otp_expires_at' => null,
            ]);

            return back()->with('fail', 'OTP expired.');
        }

        $otpInput = preg_replace('/\D+/', '', (string) $request->input('otp', ''));
        if (!Hash::check($otpInput, $resident->phone_otp)) {
            if ($otpPurpose === 'register') {
                $attemptKey = 'register_otp_attempts_' . $resident->id;
                $attempts = (int) $request->session()->get($attemptKey, 0) + 1;
                $request->session()->put($attemptKey, $attempts);

                if ($attempts >= self::REGISTER_OTP_MAX_ATTEMPTS) {
                    return $this->cancelPendingRegistration($request, $resident, 'Too many invalid OTP attempts.');
                }

                $remaining = self::REGISTER_OTP_MAX_ATTEMPTS - $attempts;
                return back()->with('fail', 'Invalid OTP. ' . $remaining . ' attempt(s) remaining.');
            }

            return back()->with('fail', 'Invalid OTP.');
        }

        $resident->update([
            'phone_verified' => 'true',
            'phone_otp' => null,
            'phone_otp_expires_at' => null
        ]);

        $request->session()->forget('register_otp_attempts_' . $resident->id);

        $otpPurpose = (string) $request->session()->pull('otp_purpose', 'login');
        $request->session()->forget('resident_id');

        if ($otpPurpose === 'login') {
            Auth::login($resident);
            $request->session()->regenerate();
            return redirect()->intended(route('barangay_system.index'))->with('success', 'Mobile OTP verified successfully.');
        } else {
            Auth::login($resident);
            $request->session()->regenerate();
            return redirect()->intended(route('barangay_system.index'))->with('success', 'Mobile number verified. Welcome!');
        }
    }

    private function cancelPendingRegistration(Request $request, Residents $resident, string $reason)
    {
        if (!empty($resident->valid_id)) {
            $validIdPath = public_path((string) $resident->valid_id);
            if (is_file($validIdPath)) {
                @unlink($validIdPath);
            }
        }

        $resident->delete();
        $request->session()->forget([
            'resident_id',
            'otp_purpose',
            'register_otp_attempts_' . $resident->id,
        ]);

        return redirect()->route('register')
            ->with('fail', $reason . ' Registration has been cancelled. Please register again.');
    }
}