<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Services\MaintenanceService;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function run(Request $request, MaintenanceService $maintenance)
    {
        $configured = (string) config('maintenance.maintenance_token', '');
        $provided = (string) ($request->header('X-Maintenance-Token') ?: $request->bearerToken() ?: '');

        if ($configured === '' || $provided === '' || ! hash_equals($configured, $provided)) {
            abort(404);
        }

        $result = $maintenance->runDue();
        $status = in_array('failed', [$result['forecast'] ?? null, $result['backup'] ?? null], true) ? 500 : 200;

        return response()->json($result, $status)
            ->header('Cache-Control', 'no-store, max-age=0');
    }
}
