<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangayClearance;
use App\Models\Residents;
use App\Models\ResidencyApplication;
use App\Models\IndigencyApplication;
use App\Models\BlotterReport;
use App\Models\Event;
use App\Services\ForecastService;
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
            'upcomingEvents' => Event::whereDate('event_date', '>=', now('Asia/Manila')->toDateString())->count(),
        ];
    }

    public function index(ForecastService $forecast)
    {
        $data = $forecast->data(true);
        $updatedAt = $forecast->updatedAt($data);
        $forecastLastUpdated = $updatedAt?->format('M d, Y h:i A') . ($updatedAt ? ' PHT' : '');
        $stats = $this->getDashboardStats();

        return view('admin.dashboard', compact('stats', 'forecastLastUpdated'));
    }

    public function liveStats(ForecastService $forecast)
    {
        $data = $forecast->data(false);
        $updatedAt = $forecast->updatedAt($data);

        return response()->json([
            'stats' => $this->getDashboardStats(),
            'forecastLastUpdated' => $updatedAt ? $updatedAt->format('M d, Y h:i A') . ' PHT' : null,
            'serverTime' => now()->toIso8601String(),
        ]);
    }

    public function forecastRequests(ForecastService $forecast)
    {
        try {
            $data = $forecast->data(true);

            if ($data === null) {
                return response()->json([
                    'error' => 'Forecast has not been generated yet.',
                    'applications' => [],
                    'blotter_reports' => [],
                    'announcements' => [],
                ], 503);
            }

            return response()->json($data)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        } catch (\Throwable $e) {
            Log::error('Forecast endpoint failed.', ['message' => $e->getMessage()]);

            return response()->json([
                'error' => 'Forecast data is temporarily unavailable.',
                'applications' => [],
                'blotter_reports' => [],
                'announcements' => [],
            ], 500);
        }
    }

    public function refreshForecast(ForecastService $forecast)
    {
        try {
            $data = $forecast->refresh();
            $updatedAt = $forecast->updatedAt($data);

            return response()->json([
                'success' => true,
                'message' => 'Forecast refreshed successfully.',
                'forecastLastUpdated' => $updatedAt ? $updatedAt->format('M d, Y h:i A') . ' PHT' : null,
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        } catch (\Throwable $e) {
            Log::error('Forecast refresh failed.', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh forecast. Please check the server logs.',
            ], 500);
        }
    }
}
