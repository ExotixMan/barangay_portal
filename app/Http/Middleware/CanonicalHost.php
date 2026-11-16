<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('production') && ! $request->is('up')) {
            $hosts = array_filter(array_merge(
                [parse_url(config('app.url'), PHP_URL_HOST)],
                config('app.allowed_hosts', [])
            ));
            abort_unless(in_array(strtolower($request->getHost()), array_map('strtolower', $hosts), true), 400);
        }

        return $next($request);
    }
}
