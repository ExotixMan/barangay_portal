<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        
        $response = $next($request);

        if ($request->routeIs('admin.*', 'profile*', 'secure.*') || auth('web')->check()) {
            $response->headers->set('Cache-Control', 'private, no-store, max-age=0');
        }

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        $response->headers->set(
            'Content-Security-Policy',
            "frame-ancestors 'self'"
        );

        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000'
            );
        }

        return $response;
    }
}
