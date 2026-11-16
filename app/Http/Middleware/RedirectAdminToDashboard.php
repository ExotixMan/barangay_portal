<?php

namespace App\Http\Middleware;

use App\Support\AdminLandingPage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminToDashboard
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            Auth::guard('admin')->check()
            && ! $request->routeIs('admin.*')
        ) {
            return redirect()->route(AdminLandingPage::routeFor(Auth::guard('admin')->user()));
        }

        return $next($request);
    }
}
