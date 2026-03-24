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
    /**
     * Build current dashboard stats snapshot.
     */
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

    /**
     * Display the admin dashboard with initial stats
     */
    public function index()
    {
        $forecastPath = public_path('analytics/forecast.json');

        $forecastLastUpdated = file_exists($forecastPath)
            ? \Carbon\Carbon::createFromTimestamp(filemtime($forecastPath), 'Asia/Manila')->format('M d, Y h:i A') . ' PHT'
            : null;

        $stats = $this->getDashboardStats();

        return view('admin.dashboard', compact('stats', 'forecastLastUpdated'));
    }

    /**
     * Return live dashboard stats for realtime card updates.
     */
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

    /**
     * Fetch forecast data from cached JSON file
     */
    public function forecastRequests()
    {
        try {
            // Read pre-generated forecast JSON file (fast path)
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

    /**
     * Trigger forecast regeneration from dashboard action.
     */
    public function refreshForecast()
    {
        try {
            Artisan::call('analytics:refresh-forecast');

            $forecastPath = public_path('analytics/forecast.json');
            $forecastLastUpdated = file_exists($forecastPath)
                ? \Carbon\Carbon::createFromTimestamp(filemtime($forecastPath), 'Asia/Manila')->format('M d, Y h:i A') . ' PHT'
                : null;

            return response()->json([
                'success' => true,
                'message' => 'Forecast refreshed successfully.',
                'forecastLastUpdated' => $forecastLastUpdated,
            ]);
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
