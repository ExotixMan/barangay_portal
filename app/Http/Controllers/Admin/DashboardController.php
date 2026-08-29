<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangayClearance;
use App\Models\Residents;
use App\Models\ResidencyApplication;
use App\Models\IndigencyApplication;
use App\Models\BlotterReport;
use App\Models\Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    
    private function getDashboardStats(): array
    {
        $pendingStatuses = ['pending', 'under_review', 'processing'];

        $pendingRequests =
            BarangayClearance::whereIn('status', $pendingStatuses)->count() +
            ResidencyApplication::whereIn('status', $pendingStatuses)->count() +
            IndigencyApplication::whereIn('status', $pendingStatuses)->count();

        return [
            'totalResidents' => Residents::count(),
            'pendingRequests' => $pendingRequests,
            'openReports' => BlotterReport::where('status', 'processing')->count(),
            'upcomingEvents' => Event::where('event_date', '>=', now())->count(),
        ];
    }

    public function index()
    {
        $forecastPath = public_path('analytics/forecast.json');

        $forecastLastUpdated = file_exists($forecastPath)
            ? \Carbon\Carbon::createFromTimestamp(filemtime($forecastPath), 'Asia/Manila')->format('M d, Y h:i A') . ' PHT'
            : null;

        $stats = $this->getDashboardStats();

        return view('admin.dashboard', compact('stats', 'forecastLastUpdated'));
    }

    public function liveStats()
    {
        $forecastPath = public_path('analytics/forecast.json');
        $forecastLastUpdated = file_exists($forecastPath)
            ? \Carbon\Carbon::createFromTimestamp(filemtime($forecastPath), 'Asia/Manila')->format('M d, Y h:i A') . ' PHT'
            : null;

        return response()->json([
            'stats' => $this->getDashboardStats(),
            'forecastLastUpdated' => $forecastLastUpdated,
            'serverTime' => now()->toIso8601String(),
        ]);
    }

    public function forecastRequests()
    {
        try {
            
            $forecastPath = public_path('analytics/forecast.json');

            if (!file_exists($forecastPath)) {
                Log::warning('Forecast file not found at: ' . $forecastPath);
                return response()->json([
                    'error' => 'Forecast file not generated yet',
                    'applications' => [],
                    'blotter_reports' => [],
                    'announcements' => []
                ], 404);
            }

            $forecast = json_decode(file_get_contents($forecastPath), true);

            if (!$forecast) {
                Log::error('Failed to decode forecast JSON');
                return response()->json([
                    'error' => 'Invalid forecast data',
                    'applications' => [],
                    'blotter_reports' => [],
                    'announcements' => []
                ], 500);
            }

            return response()->json($forecast);

        } catch (\Exception $e) {
            Log::error('Forecast error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'applications' => [],
                'blotter_reports' => [],
                'announcements' => []
            ], 500);
        }
    }

    public function refreshForecast()
    {
        try {
            $exitCode = Artisan::call('analytics:refresh-forecast');
            $commandOutput = Artisan::output();

            if ($exitCode !== 0) {
                Log::error('Forecast command failed.', [
                    'exit_code' => $exitCode,
                    'output' => $commandOutput,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Forecast command failed.',
                    'error' => $commandOutput ?: 'No command output.',
                ], 500);
            }

            $forecastPath = public_path('analytics/forecast.json');

            clearstatcache(true, $forecastPath);

            $forecastLastUpdated = file_exists($forecastPath)
                ? \Carbon\Carbon::createFromTimestamp(filemtime($forecastPath))
                    ->timezone('Asia/Manila')
                    ->format('M d, Y h:i A') . ' PHT'
                : null;

            return response()->json([
                'success' => true,
                'message' => 'Forecast refreshed successfully.',
                'forecastLastUpdated' => $forecastLastUpdated,
                'output' => $commandOutput,
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

        } catch (\Throwable $e) {
            Log::error('Forecast refresh command failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh forecast.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
