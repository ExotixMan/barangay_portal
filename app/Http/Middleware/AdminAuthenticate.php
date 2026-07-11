<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        // Allow authenticated administrators.
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // Redirect authenticated residents to the resident homepage.
        if (Auth::guard('web')->check()) {
            return redirect()->route('barangay_system.index');
        }

        // Unauthenticated AJAX/API request.
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Unauthenticated.',
            ], 401);
        }

        // Guest user goes to admin login.
        return redirect()->guest(route('barangay_system.login'));
    }
}