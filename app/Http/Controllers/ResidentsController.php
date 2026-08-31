<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use App\Models\Residents;
use App\Models\Announcement;
use App\Services\OtpSmsService;
use App\Services\SupabaseStorageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Services\AdminSettingsService;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Mail;

class ResidentsController extends Controller
{
    private const REGISTER_OTP_MAX_ATTEMPTS = 3;
    private const LOGIN_OTP_MAX_ATTEMPTS = 5;

    public function __construct(
        private OtpSmsService $otpSmsService,
        private AdminSettingsService $settings
    ) {
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

        $captchaEnabled = (bool) $this->settings->get('security.captcha_enabled', false);

        $rules = [
            'firstname' => 'required|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'middlename' => 'nullable|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'lastname' => 'required|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'suffix' => 'nullable|string|max:10|in:None,Jr.,Sr.,II,III,IV,V',
            'email' => 'required|email|unique:residents,email',
            'address' => 'required|string|max:500',
            'birthdate' => 'required|date|before:18 years ago',
            'gender' => 'required|string|in:Male,Female,Prefer not to say',
            'civil_status' => 'required|string|in:Single,Married,Widowed,Separated',
            'place_of_birth' => 'required|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'contact' => 'required|string|max:11|regex:/^09\d{9}$/|unique:residents,contact',

            'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id_back' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id_selfie' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',

            'username' => 'required|string|unique:residents,username|min:4|max:50|regex:/^[a-zA-Z0-9_.-]+$/',
            'password' => ['required','string','confirmed','regex:/^\S+$/', PasswordRules::min(8)->mixedCase()->numbers()->symbols()],
            'terms' => 'accepted',
        ];

        if ($captchaEnabled) {
            $rules['g-recaptcha-response'] = 'required|string';
        }

        $messages = [
            'firstname.regex' => 'First name may only contain letters, spaces, dots, apostrophes, and hyphens',
            'middlename.regex' => 'Middle name may only contain letters, spaces, dots, apostrophes, and hyphens',
            'lastname.regex' => 'Last name may only contain letters, spaces, dots, apostrophes, and hyphens',
            'suffix.in' => 'Please select a valid suffix',
            'contact.regex' => 'Please enter a valid Philippine mobile number (09XXXXXXXXX)',
            'contact.unique' => 'This contact number is already registered',
            'birthdate.before' => 'You must be at least 18 years old to register',
            'gender.required' => 'Please select your gender.',
            'gender.in' => 'Please select a valid gender.',
            'civil_status.required' => 'Please select your civil status.',
            'civil_status.in' => 'Please select a valid civil status.',
            'place_of_birth.required' => 'Place of birth is required.',
            'place_of_birth.regex' => 'Place of birth may only contain letters, spaces, dots, commas, apostrophes, and hyphens.',
            'password.regex' => 'Password cannot contain spaces',
            'password.*' => 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.',
            'username.regex' => 'Username may only contain letters, numbers, underscores, dots, and hyphens (no spaces)',
            'username.min' => 'Username must be at least 4 characters long',
            'valid_id.required' => 'Please scan or upload the front side of your valid ID.',
            'valid_id_back.required' => 'Please scan or upload the back side of your valid ID.',
            'valid_id.mimes' => 'The front valid ID must be a JPG, JPEG, PNG, or PDF file.',
            'valid_id_back.mimes' => 'The back valid ID must be a JPG, JPEG, PNG, or PDF file.',
            'valid_id_selfie.mimes' => 'The selfie holding ID must be a JPG, JPEG, or PNG file.',
            'valid_id.max' => 'The front valid ID must not be larger than 5MB.',
            'valid_id_back.max' => 'The back valid ID must not be larger than 5MB.',
            'valid_id_selfie.max' => 'The selfie holding ID must not be larger than 5MB.',
            'terms.accepted' => 'You must agree to the Terms and Conditions and Privacy Policy',
            'g-recaptcha-response.required' => 'Please complete the CAPTCHA verification',
        ];

        $data = $request->validate($rules, $messages);

        if ($captchaEnabled && ! $this->verifyRecaptcha(
            (string) $request->input('g-recaptcha-response'),
            (string) $request->ip()
        )) {
            return back()
                ->withErrors(['g-recaptcha-response' => 'CAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        unset($data['terms'], $data['g-recaptcha-response'], $data['password_confirmation']);

        $uploadedPaths = [];

        try {
            foreach (['valid_id', 'valid_id_back', 'valid_id_selfie'] as $field) {
                if ($request->hasFile($field)) {
                    $data[$field] = $this->storeResidentValidIdFile($request, $field);
                    $uploadedPaths[] = $data[$field];
                }
            }

            if (isset($data['suffix']) && $data['suffix'] === 'None') {
                $data['suffix'] = null;
            }

            $otp = random_int(100000, 999999);

            $data['password'] = Hash::make($data['password']);
            $data['phone_otp'] = Hash::make($otp);
            $data['phone_otp_expires_at'] = Carbon::now()->addMinutes(5);

            $data['phone_verified'] = 'false';
            $data['valid_id_verified'] = 'false';

            $data['valid_id_status'] = 'for_verification';
            $data['valid_id_verified_at'] = null;
            $data['valid_id_verified_by'] = null;
            $data['valid_id_rejection_reason'] = null;

            $resident = Residents::create($data);

            try {
                $this->sendOtpAndLog($resident, $otp, 'register');
            } catch (\Throwable $exception) {
                
                $this->deleteResidentValidIdFiles($resident);
                $resident->forceDelete();

                throw $exception;
            }

            $request->session()->put('resident_id', $resident->id);
            $request->session()->put('otp_purpose', 'register');

            return redirect()->route('otp.form')
                ->with('success', 'Registration successful. Please verify your mobile number with OTP.');
        } catch (\Throwable $exception) {
            foreach ($uploadedPaths as $path) {
                $this->deleteResidentValidIdFile($path);
            }

            return back()
                ->with('fail', 'Registration failed. Please try again.')
                ->withInput();
        }
    }

    private function verifyRecaptcha(string $token, string $ip): bool
    {
        $secret = (string) $this->settings->get(
            'security.recaptcha_secret_key',
            config('services.recaptcha.secret_key', '')
        );

        if ($secret === '' || $token === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(8)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $ip,
                ]);

            if (!$response->ok()) {
                return false;
            }

            $payload = $response->json();
            return (bool) data_get($payload, 'success', false);
        } catch (\Throwable $exception) {
            return false;
        }
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

   public function checkAccountAvailability(Request $request)
    {
        $field = (string) $request->query('field', 'username');
        $value = trim((string) $request->query('value', ''));

        $rules = [
            'username' => [
                'label' => 'Username',
                'normalize' => fn (string $input) => strtolower(trim($input)),
                'pattern' => '/^[a-zA-Z0-9_.-]+$/',
                'min' => 4,
                'column' => 'username',
                'empty_message' => 'Username is required.',
                'format_message' => 'Username may only contain letters, numbers, underscores, dots, and hyphens.',
                'min_message' => 'Username must be at least 4 characters.',
            ],
            'email' => [
                'label' => 'Email',
                'normalize' => fn (string $input) => strtolower(trim($input)),
                'pattern' => '/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
                'min' => 6,
                'column' => 'email',
                'empty_message' => 'Email is required.',
                'format_message' => 'Please enter a valid email address.',
                'min_message' => 'Please enter a valid email address.',
            ],
            'contact' => [
                'label' => 'Contact number',
                'normalize' => fn (string $input) => preg_replace('/\D+/', '', $input),
                'pattern' => '/^09\d{9}$/',
                'min' => 11,
                'column' => 'contact',
                'empty_message' => 'Contact number is required.',
                'format_message' => 'Please enter a valid Philippine mobile number (09XXXXXXXXX).',
                'min_message' => 'Contact number must be 11 digits.',
            ],
        ];

        if (!isset($rules[$field])) {
            return response()->json([
                'available' => false,
                'message' => 'Unsupported field.',
            ]);
        }

        $rule = $rules[$field];
        $normalized = ($rule['normalize'])($value);

        if ($normalized === '') {
            return response()->json([
                'available' => false,
                'message' => $rule['empty_message'],
            ]);
        }

        if (strlen($normalized) < $rule['min']) {
            return response()->json([
                'available' => false,
                'message' => $rule['min_message'],
            ]);
        }

        if (!preg_match($rule['pattern'], $normalized)) {
            return response()->json([
                'available' => false,
                'message' => $rule['format_message'],
            ]);
        }

        $exists = Residents::where($rule['column'], $normalized)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists
                ? $rule['label'] . ' is already taken.'
                : $rule['label'] . ' is available.',
        ]);
    }

    public function login_res(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $rawLoginInput = trim((string) $request->input('login'));
        $password = (string) $request->input('password');
        $loginOtpEnabled = (bool) $this->settings->get('security.login_otp_enabled', 'false');

        $adminLoginField = filter_var($rawLoginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $admin = AdminUser::where($adminLoginField, $rawLoginInput)->first();

        if ($admin && Hash::check($password, (string) $admin->password)) {
            if ($admin->status !== 'active') {
                return back()
                    ->withErrors(['login' => 'Your admin account is not active. Please contact the administrator.'])
                    ->onlyInput('login');
            }

            if (!$loginOtpEnabled) {
                Auth::guard('admin')->login($admin);
                $request->session()->regenerate();

                DB::table('admin_users')
                    ->where('id', $admin->id)
                    ->update(['last_login_at' => now('Asia/Manila')]);

                $request->session()->put('login_time', now('Asia/Manila'));

                return $this->redirectAdminBasedOnRole($admin);
            }

            $otp = random_int(100000, 999999);

            $request->session()->put([
                'otp_account_type' => 'admin',
                'admin_otp_user_id' => $admin->id,
                'admin_otp_hash' => Hash::make((string) $otp),
                'admin_otp_expires_at' => now('Asia/Manila')->addMinutes(5)->toDateTimeString(),
            ]);

            
            $this->sendAdminOtpSms($admin, $otp);

            

            return redirect()->route('otp.form')
                ->with('success', 'Admin OTP has been sent to your contact number.');
        }

        $loginInput = $rawLoginInput;
        $loginType = 'username';

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

        if (!$resident || !Hash::check($password, (string) $resident->password)) {
            return back()
                ->withErrors(['login' => 'Invalid username/email/mobile number or password.'])
                ->onlyInput('login');
        }

        if ($resident->valid_id_status === 'rejected') {
            $request->session()->regenerate();
            $request->session()->put('valid_id_resubmit_resident_id', $resident->id);

            return redirect()
                ->route('valid-id.resubmit.form')
                ->with('warning', $this->validIdRejectionMessage($resident));
        }

        if ($message = $this->validIdLoginBlockMessage($resident)) {
            return back()
                ->withErrors(['login' => $message])
                ->onlyInput('login');
        }

        if (! $loginOtpEnabled) {
            $resident->update([
                'phone_otp' => null,
                'phone_otp_expires_at' => null,
            ]);

            Auth::login($resident);
            $request->session()->regenerate();

            return redirect()
                ->intended(route('barangay_system.index'))
                ->with('success', 'Logged in successfully.');
        }

        $otp = random_int(100000, 999999);

        $resident->update([
            'phone_otp' => Hash::make((string) $otp),
            'phone_otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $this->sendOtpAndLog($resident, $otp, 'login');

        $request->session()->put('otp_account_type', 'resident');
        $request->session()->put('resident_id', $resident->id);
        $request->session()->put('otp_purpose', 'login');

        return redirect()->route('otp.form')
            ->with('success', 'Please verify your mobile number with OTP to continue.');
    }
    
    public function showValidIdResubmission(Request $request)
    {
        $residentId = $request->session()->get('valid_id_resubmit_resident_id');
        $resident = $residentId ? Residents::find($residentId) : null;

        if (!$resident || $resident->valid_id_status !== 'rejected') {
            $request->session()->forget('valid_id_resubmit_resident_id');

            return redirect()->route('login')
                ->with('fail', 'Please sign in again to resubmit your valid ID.');
        }

        return view('barangay_system.valid_id_resubmit', compact('resident'));
    }

    public function resubmitValidId(Request $request)
    {
        $residentId = $request->session()->get('valid_id_resubmit_resident_id');
        $resident = $residentId ? Residents::find($residentId) : null;

        if (!$resident || $resident->valid_id_status !== 'rejected') {
            $request->session()->forget('valid_id_resubmit_resident_id');

            return redirect()->route('login')
                ->with('fail', 'Your valid ID resubmission session expired. Please sign in again.');
        }

        $request->validate([
            'valid_id' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'valid_id_back' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'valid_id.required' => 'Please upload the front side of your valid ID.',
            'valid_id_back.required' => 'Please upload the back side of your valid ID.',
            'valid_id.mimes' => 'The front valid ID must be JPG, JPEG, PNG, or PDF.',
            'valid_id_back.mimes' => 'The back valid ID must be JPG, JPEG, PNG, or PDF.',
            'valid_id.max' => 'The front valid ID must not exceed 5MB.',
            'valid_id_back.max' => 'The back valid ID must not exceed 5MB.',
        ]);

        $frontFile = $request->file('valid_id');
        $backFile = $request->file('valid_id_back');

        if (!$frontFile || !$backFile || !$frontFile->isValid() || !$backFile->isValid()) {
            return back()->withErrors(['valid_id' => 'Please upload valid front and back ID files.']);
        }

        if ($this->uploadedFilesAreIdentical($frontFile, $backFile)) {
            return back()->withErrors([
                'valid_id_back' => 'The back ID appears to be the same file as the front. Please upload the back side.',
            ]);
        }

        $newPaths = [];
        $oldFront = $resident->valid_id;
        $oldBack = $resident->valid_id_back;

        try {
            $frontPath = $this->storeResidentValidIdFile($request, 'valid_id');
            $newPaths[] = $frontPath;
            $backPath = $this->storeResidentValidIdFile($request, 'valid_id_back');
            $newPaths[] = $backPath;

            $resident->update([
                'valid_id' => $frontPath,
                'valid_id_back' => $backPath,
                'valid_id_verified' => DB::raw('FALSE'),
                'valid_id_status' => 'for_verification',
                'valid_id_verified_at' => null,
                'valid_id_verified_by' => null,
                'valid_id_rejection_reason' => null,
            ]);

            $this->deleteResidentValidIdFile($oldFront);
            $this->deleteResidentValidIdFile($oldBack);
        } catch (\Throwable $exception) {
            foreach ($newPaths as $path) {
                $this->deleteResidentValidIdFile($path);
            }

            report($exception);

            return back()->with('fail', 'Valid ID resubmission failed. Please try again.');
        }

        $request->session()->forget('valid_id_resubmit_resident_id');

        return redirect()->route('login')
            ->with('success', 'Your new valid ID was submitted and is waiting for barangay verification.');
    }

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

        $this->sendOtpAndLog($resident, $otp, $otpPurpose);

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

        return redirect('/login')->with('success', 'Logged out successfully.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'resident_id' => 'nullable|exists:residents,id',
        ]);

        $accountType = (string) $request->session()->get('otp_account_type', 'resident');

        if ($accountType === 'admin') {
            $adminId = $request->session()->get('admin_otp_user_id');
            $otpHash = $request->session()->get('admin_otp_hash');
            $expiresAt = $request->session()->get('admin_otp_expires_at');

            if (!$adminId || !$otpHash || !$expiresAt) {
                return redirect()->route('login')
                    ->with('fail', 'OTP session expired. Please login again.');
            }

            if (now('Asia/Manila')->gt(Carbon::parse($expiresAt))) {
                $request->session()->forget([
                    'otp_account_type',
                    'admin_otp_user_id',
                    'admin_otp_hash',
                    'admin_otp_expires_at',
                ]);

                return redirect()->route('login')
                    ->with('fail', 'Admin OTP expired. Please login again.');
            }

            $otpInput = preg_replace('/\D+/', '', (string) $request->input('otp'));

            if (!Hash::check($otpInput, $otpHash)) {
                $attemptKey = 'admin_otp_attempts_' . $adminId;
                $attempts = (int) $request->session()->get($attemptKey, 0) + 1;
                $request->session()->put($attemptKey, $attempts);

                if ($attempts >= self::LOGIN_OTP_MAX_ATTEMPTS) {
                    $request->session()->forget([
                        'otp_account_type', 'admin_otp_user_id', 'admin_otp_hash',
                        'admin_otp_expires_at', $attemptKey,
                    ]);
                    return redirect()->route('login')->with('fail', 'Too many invalid OTP attempts. Please login again.');
                }

                return back()->with('fail', 'Invalid OTP. ' . (self::LOGIN_OTP_MAX_ATTEMPTS - $attempts) . ' attempt(s) remaining.');
            }

            $request->session()->forget('admin_otp_attempts_' . $adminId);

            $admin = AdminUser::find($adminId);

            if (!$admin) {
                return redirect()->route('login')
                    ->with('fail', 'Admin account not found.');
            }

            if ($admin->status !== 'active') {
                return redirect()->route('login')
                    ->withErrors(['login' => 'Your admin account is not active. Please contact the administrator.']);
            }

            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();

            DB::table('admin_users')
                ->where('id', $admin->id)
                ->update(['last_login_at' => now()]);

            $request->session()->put('login_time', now());

            $request->session()->forget([
                'otp_account_type',
                'admin_otp_user_id',
                'admin_otp_hash',
                'admin_otp_expires_at',
            ]);

            return $this->redirectAdminBasedOnRole($admin);
        }

        $otpPurpose = (string) $request->session()->get('otp_purpose', 'login');

        $residentId = $request->session()->get('resident_id', $request->input('resident_id'));

        if (!$residentId) {
            return redirect()->route('login')
                ->with('fail', 'OTP session expired. Please login again.');
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

        $otpInput = preg_replace('/\D+/', '', (string) $request->input('otp'));

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

            $attemptKey = 'login_otp_attempts_' . $resident->id;
            $attempts = (int) $request->session()->get($attemptKey, 0) + 1;
            $request->session()->put($attemptKey, $attempts);

            if ($attempts >= self::LOGIN_OTP_MAX_ATTEMPTS) {
                $resident->update(['phone_otp' => null, 'phone_otp_expires_at' => null]);
                $request->session()->forget($attemptKey);
                return redirect()->route('login')->with('fail', 'Too many invalid OTP attempts. Please login again.');
            }

            return back()->with('fail', 'Invalid OTP. ' . (self::LOGIN_OTP_MAX_ATTEMPTS - $attempts) . ' attempt(s) remaining.');
        }

        $request->session()->forget('login_otp_attempts_' . $resident->id);

        $resident->update([
            'phone_verified' => DB::raw('TRUE'),
            'phone_otp' => null,
            'phone_otp_expires_at' => null,
        ]);

        $request->session()->forget('register_otp_attempts_' . $resident->id);

        $otpPurpose = (string) $request->session()->pull('otp_purpose', 'login');

        $request->session()->forget([
            'resident_id',
            'otp_account_type',
        ]);

        if ($otpPurpose === 'login') {
            if ($message = $this->validIdLoginBlockMessage($resident)) {
                return redirect()
                    ->route('login')
                    ->withErrors(['login' => $message]);
            }

            Auth::login($resident);
            $request->session()->regenerate();

            return redirect()
                ->intended(route('barangay_system.index'))
                ->with('success', 'Mobile OTP verified successfully.');
        }

        return redirect()
            ->route('login')
            ->with('success', 'Mobile number verified. Your account is now waiting for admin valid ID approval.');
    }

    private function sendOtpAndLog(Residents $resident, int $otp, string $purpose): void
    {
        try {
            $this->otpSmsService->sendOtp($resident, $otp, $purpose);

            $this->logOtpSms(
                resident: $resident,
                purpose: $purpose,
                status: 'sent'
            );
        } catch (\Throwable $exception) {
            $this->logOtpSms(
                resident: $resident,
                purpose: $purpose,
                status: 'failed',
                errorMessage: $exception->getMessage()
            );

            throw $exception;
        }
    }

    private function logOtpSms(
        Residents $resident,
        string $purpose,
        string $status,
        ?string $errorMessage = null
    ): void {
        if (! Schema::hasTable('notification_logs')) {
            return;
        }

        DB::table('notification_logs')->insert([
            'channel' => 'sms',
            'recipient' => $resident->contact,
            'status' => $status,
            'message' => match ($purpose) {
                'register' => 'Registration OTP sent.',
                'login' => 'Login OTP sent.',
                default => 'OTP sent.',
            },
            'error_message' => $errorMessage,
            'reference_type' => 'otp_' . $purpose,
            'reference_id' => $resident->id,
            'sent_by' => null,
            'sent_at' => $status === 'sent' ? now('Asia/Manila') : null,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function cancelPendingRegistration(Request $request, Residents $resident, string $reason)
    {
        $this->deleteResidentValidIdFiles($resident);

        $resident->delete();
        $request->session()->forget([
            'resident_id',
            'otp_purpose',
            'register_otp_attempts_' . $resident->id,
        ]);

        return redirect()->route('register')
            ->with('fail', $reason . ' Registration has been cancelled. Please register again.');
    }

    private function validIdLoginBlockMessage(Residents $resident): ?string
    {
        if (empty($resident->valid_id) || empty($resident->valid_id_back)) {
            return 'Your account cannot login yet because the front and back valid ID scans are incomplete.';
        }

        if ($resident->valid_id_status === 'rejected') {
            return $this->validIdRejectionMessage($resident);
        }

        if ((bool) $resident->valid_id_verified !== true || $resident->valid_id_status !== 'verified') {
            return 'Your account cannot login yet. Please wait until the admin approves your valid ID.';
        }

        return null;
    }

    private function validIdRejectionMessage(Residents $resident): string
    {
        $reason = trim((string) $resident->valid_id_rejection_reason);

        return $reason !== ''
            ? 'Your valid ID was rejected. Reason: ' . $reason . ' Please submit a new front and back copy.'
            : 'Your valid ID was rejected. Please submit a clearer front and back copy.';
    }

    private function uploadedFilesAreIdentical($frontFile, $backFile): bool
    {
        $frontPath = $frontFile->getRealPath();
        $backPath = $backFile->getRealPath();

        if (!$frontPath || !$backPath || !is_file($frontPath) || !is_file($backPath)) {
            return false;
        }

        return hash_file('sha256', $frontPath) === hash_file('sha256', $backPath);
    }

    private function storeResidentValidIdFile(Request $request, string $field): string
    {
        $file = $request->file($field);

        if (!$file || !$file->isValid()) {
            throw new \RuntimeException('The uploaded file for ' . $field . ' is invalid.');
        }

        $uploadDirectory = 'private/valid_id/residents';

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = $field . '_' . now('Asia/Manila')->format('Ymd_His') . '_' . uniqid('', true) . '.' . $extension;

        return app(SupabaseStorageService::class)->storePrivateFileWithName(
            $file,
            'valid_id/residents',
            $filename
        );
    }

    private function deleteResidentValidIdFiles(Residents $resident): void
    {
        foreach (['valid_id', 'valid_id_back', 'valid_id_selfie'] as $field) {
            $this->deleteResidentValidIdFile((string) $resident->{$field});
        }
    }

    private function deleteResidentValidIdFile(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        app(SupabaseStorageService::class)->delete($path);
    }

    private function redirectAdminBasedOnRole(AdminUser $user)
    {
        if (method_exists($user, 'load')) {
            $user->load('role');
        }

        $permissionRoutes = [
            'view_forecast' => 'admin.dashboard.index',
            'view_dashboard' => 'admin.dashboard.index',
            'view_residents' => 'admin.residents.index',
            'view_residency' => 'admin.residency.index',
            'view_indigency' => 'admin.indigency.index',
            'view_clearance' => 'admin.clearance.index',
            'view_blotter' => 'admin.blotter.index',
            'view_announcements' => 'admin.announcements.index',
            'view_events' => 'admin.events.index',
            'view_projects' => 'admin.projects.index',
            'view_requests' => 'admin.request.index',
            'view_users' => 'admin.users.index',
            'view_roles' => 'admin.roles.index',
            'view_permissions' => 'admin.permissions.index',
        ];

        foreach ($permissionRoutes as $permission => $route) {
            if ($this->adminHasPermission($user, $permission)) {
                return redirect()->intended(route($route));
            }
        }

        return redirect()->intended(route('admin.dashboard.index'));
    }

    private function adminHasPermission(AdminUser $user, string $permissionName): bool
    {
        if ($user->role && $user->role->name === 'super_admin') {
            return true;
        }

        if ($user->role && method_exists($user->role, 'permissions')) {
            return $user->role->permissions()
                ->where('name', $permissionName)
                ->exists();
        }

        return false;
    }

    private function sendAdminOtpSms(AdminUser $admin, int $otp): void
    {
        $contact = preg_replace('/\D+/', '', (string) $admin->contact_number);

        if (str_starts_with($contact, '09')) {
            $contact = '63' . substr($contact, 1);
        } elseif (str_starts_with($contact, '9')) {
            $contact = '63' . $contact;
        }

        if (!preg_match('/^639\d{9}$/', $contact)) {
            throw new \RuntimeException('Admin contact number is invalid or missing.');
        }

        $message = "Your admin login OTP is {$otp}. It will expire in 5 minutes.";

        $response = Http::asForm()
            ->timeout(15)
            ->post('https://api.semaphore.co/api/v4/messages', [
                'apikey' => env('SMS_API_KEY'),
                'number' => $contact,
                'message' => $message,
                'sendername' => env('SMS_SENDER_NAME', 'HULONGDUHAT'),
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException('Failed to send admin OTP SMS: ' . $response->body());
        }
    }
}
