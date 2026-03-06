<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // global middleware
    ];

    protected $routeMiddleware = [
        // ... other middleware
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'admin.auth' => \App\Http\Middleware\AdminAuthenticate::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\SetLocale::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

    ];

    // ...
}
