<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Prevent clickjacking.
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Modern equivalent for controlling iframe embedding.
        $response->headers->set(
            'Content-Security-Policy',
            "frame-ancestors 'self'"
        );

        // Prevent MIME-type sniffing.
        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        // Send HSTS only through HTTPS.
        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000'
            );
        }

        return $response;
    }
}