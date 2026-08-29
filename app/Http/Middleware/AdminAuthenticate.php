<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        
        if (Auth::guard('web')->check()) {
            return redirect()->route('barangay_system.index');
        }

        
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Unauthenticated.',
            ], 401);
        }

        
        return redirect()->guest(route('login'));
    }
}