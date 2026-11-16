<?php

use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CanonicalHost;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\MaintenanceCatchUp;
use App\Http\Middleware\NoPendingProfileCorrection;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\RedirectAdminToDashboard;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\ValidateRequestInput;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Render terminates HTTPS at its proxy. Trust forwarded headers so Laravel
        // generates HTTPS URLs for assets, redirects, cookies, and route helpers.
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
        );

        $middleware->prepend(CanonicalHost::class);
        $middleware->append(SecurityHeaders::class);
        $middleware->append(MaintenanceCatchUp::class);

        $middleware->validateCsrfTokens(except: ['internal/maintenance/run']);

        $middleware->web(append: [
            SetLocale::class,
            RedirectAdminToDashboard::class,
            ValidateRequestInput::class,
        ]);

        $middleware->alias([
            'prevent-back-history' => PreventBackHistory::class,
            'no-pending-correction' => NoPendingProfileCorrection::class,

            'admin' => AdminMiddleware::class,
            'admin.auth' => AdminAuthenticate::class,
            'permission' => CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontFlash(['new_password', 'new_password_confirmation', 'otp', 'phone_otp', 'contact_otp']);
    })->create();
