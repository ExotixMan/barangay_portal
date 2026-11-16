<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as PasswordRules;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = \App\Models\Residents::whereRaw('LOWER(email) = ?', [mb_strtolower(trim($request->email))])->value('email');
        try {
            if ($email) {
                Password::sendResetLink(['email' => $email]);
            }
        } catch (\Throwable $e) {
            report($e);
            // Use the same acknowledgement for every address to prevent enumeration.
        }

        return back()->with('status', 'If this email belongs to an account, a reset link will be sent. If no email arrives, please try again later or contact the barangay office.');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => ['required', 'string', 'confirmed', 'regex:/^\S+$/', PasswordRules::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'password.confirmed' => 'The passwords do not match.',
            'password.regex'     => 'Password cannot contain spaces.',
            'password.*'         => 'Password must be at least 8 characters and contain uppercase, lowercase, a number, and a special character.',
        ]);

        $email = \App\Models\Residents::whereRaw('LOWER(email) = ?', [mb_strtolower(trim($request->email))])->value('email');
        $request->merge(['email' => $email ?: mb_strtolower(trim($request->email))]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => 'This reset link is invalid or has expired. Please request a new link.']);
    }
}
