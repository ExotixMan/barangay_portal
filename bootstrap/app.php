<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\RedirectAdminToDashboard;
use App\Http\Middleware\NoPendingProfileCorrection;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SecurityHeaders::class);

        $middleware->web(append:[
            SetLocale::class,
            RedirectAdminToDashboard::class,
        ]);

        $middleware->alias([
            'prevent-back-history' => PreventBackHistory::class,
            'no-pending-correction' => NoPendingProfileCorrection::class,

            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.auth' => \App\Http\Middleware\AdminAuthenticate::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
