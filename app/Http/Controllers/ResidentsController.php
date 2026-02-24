<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use App\Models\Residents;
use App\Models\Announcement;
use Carbon\Carbon;

class ResidentsController extends Controller
{
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
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:residents,email',
            'address' => 'required|string',
            'birthdate' => 'required|date',
            'contact' => 'required|string|max:11',
            'username' => 'required|string|unique:residents,username',
            'password' => 'required|string|confirmed'
        ]);

        $otp = rand(100000, 999999);

        $data['password'] = Hash::make($data['password']);
        
        $data['phone_otp'] = Hash::make($otp);
        $data['phone_otp_expires_at'] = Carbon::now()->addMinutes(5);
        $data['phone_verified'] = false;

        $resident = Residents::create($data);

        $resident->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful. Please verify your email.');

        // SMS Verification

        // $message = "Your Barangay OTP is: $otp. It expires in 5 minutes.";

        // $response = Http::withHeaders([
        //     'x-api-key' => env('SMS_API_KEY'),
        //     'Content-Type' => 'application/json',
        // ])->post('https://sms-api-ph-gceo.onrender.com/send/sms', [
        //     'recipient' => $resident->contact,
        //     'message' => $message,
        // ]);
        // dd($response->status(), $response->body());

        // return redirect()->route('otp.form')
        //     ->with('resident_id', $resident->id)
        //     ->with('success', 'OTP sent to your mobile number.');

        // return redirect('login')->with('success', 'Account registered successfully!');
    }

    public function login_res(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return redirect('login')
                ->with('fail', 'Invalid username or password.');
        }

        $request->session()->regenerate();

        $resident = Auth::user();

        if (is_null($resident->email_verified_at)) {
            return redirect()->route('verification.notice')
                ->with('fail', 'Please verify your email first.');
        }

        // if (!$resident->phone_verified) {
        //     return redirect()->route('otp.form')
        //         ->with('resident_id', $resident->id)
        //         ->with('fail', 'Please verify your phone first.');
        // }

        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    public function destroy(Request $request)
    {
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
