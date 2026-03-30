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
use Illuminate\Support\Facades\Log;

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
            'contact' => 'required|string|max:11|regex:/^09\d{9}$/',
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

        $otp = rand(100000, 999999);

        $data['password'] = Hash::make($data['password']);
        $data['phone_otp'] = Hash::make($otp);
        $data['phone_otp_expires_at'] = Carbon::now()->addMinutes(5);
        $data['phone_verified'] = false;

        $resident = Residents::create($data);

        // Commented out legacy email verification:
        // Auth::login($resident);
        // $resident->sendEmailVerificationNotification();
        // return redirect()->route('verification.notice')
        //     ->with('success', 'Registration successful. Please verify your email.');

        // Send OTP to mobile and redirect to OTP form
        $this->dispatchOtpToResident($resident, $otp, 'register');
        $request->session()->put('resident_id', $resident->id);
        $request->session()->put('otp_purpose', 'register');

        return redirect()->route('otp.form')
            ->with('success', 'Registration successful. Please verify your mobile number with OTP.');
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

        if (!$resident->phone_verified) {
            $otp = rand(100000, 999999);
            $resident->update([
                'phone_otp' => Hash::make($otp),
                'phone_otp_expires_at' => Carbon::now()->addMinutes(5),
            ]);
            $this->dispatchOtpToResident($resident, $otp, 'login');
            $request->session()->put('resident_id', $resident->id);
            $request->session()->put('otp_purpose', 'login');
            return redirect()->route('otp.form')
                ->with('success', 'Please verify your mobile number with OTP to continue.');
        }

        Auth::login($resident);
        $request->session()->regenerate();

        // Commented out legacy email verification:
        // if (is_null($resident->email_verified_at)) {
        //     return redirect()->route('verification.notice');
        // }

        return redirect()->intended('/');
    }
    /**
     * Dispatch OTP to resident's mobile number (SMS logic should be implemented here)
     */
    private function dispatchOtpToResident(Residents $resident, int $otp, string $context): void
    {
        // --- SMS sending logic (Semaphore) ---
        $message = "Your Barangay Portal OTP is: $otp";
        $number = $resident->contact;
        if (app()->environment('local', 'development', 'testing')) {
            Log::info('Resident OTP generated', [
                'resident_id' => $resident->id,
                'contact' => $number,
                'context' => $context,
                'otp' => $otp,
            ]);
            session()->flash('otp_debug_code', (string) $otp);
        } else {
            try {
                $apiKey = env('SEMAPHORE_API_KEY');
                $sender = env('SEMAPHORE_SENDER_NAME', 'SEMAPHORE');
                $to = $number;
                // Ensure number is in 639XXXXXXXXX format
                if (str_starts_with($to, '0')) {
                    $to = '63' . substr($to, 1);
                }
                if (!preg_match('/^63\d{10}$/', $to)) {
                    Log::error('Semaphore: Invalid phone number format', ['to' => $to, 'original' => $number]);
                    return;
                }
                $url = 'https://api.semaphore.co/api/v4/messages';
                $payload = [
                    'apikey' => $apiKey,
                    'number' => $to,
                    'message' => $message,
                    'sendername' => $sender,
                ];
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                Log::info('Semaphore API response', ['http_code' => $httpCode, 'response' => $response]);
                $respArr = json_decode($response, true);
                if ($httpCode === 200 && is_array($respArr) && isset($respArr[0]['status']) && $respArr[0]['status'] === 'Queued') {
                    Log::info('OTP sent via Semaphore', ['to' => $to, 'context' => $context, 'response' => $response]);
                } else {
                    Log::error('Failed to send OTP via Semaphore', ['to' => $to, 'context' => $context, 'response' => $response, 'http_code' => $httpCode]);
                }
            } catch (\Throwable $e) {
                Log::error('Failed to send OTP via Semaphore', [
                    'error' => $e->getMessage(),
                    'resident_id' => $resident->id,
                    'contact' => $number,
                ]);
            }
        }
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