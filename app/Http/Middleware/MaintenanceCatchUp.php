<?php

namespace App\Http\Middleware;

use App\Services\MaintenanceService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceCatchUp
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if (! config('maintenance.request_catchup', true)) {
            return;
        }

        if ($request->is('up') || $request->is('internal/maintenance/*')) {
            return;
        }

        try {
            if (! Cache::add('maintenance:catchup-hourly', now()->timestamp, now()->addHour())) {
                return;
            }

            app(MaintenanceService::class)->runDue();
        } catch (\Throwable $e) {
            Log::warning('Maintenance catch-up check failed.', ['message' => $e->getMessage()]);
        }
    }
}
