<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function feed(Request $request): JsonResponse
    {
        $admin = $this->admin();

        $notifications = $admin->notifications()
            ->latest()
            ->limit(10)
            ->get()
            ->map(function (DatabaseNotification $notification): array {
                $data = is_array($notification->data) ? $notification->data : [];

                return [
                    'id' => $notification->id,
                    'category' => $data['category'] ?? 'notification',
                    'title' => $data['title'] ?? 'New notification',
                    'message' => $data['message'] ?? '',
                    'icon' => $data['icon'] ?? 'fas fa-bell',
                    'is_unread' => is_null($notification->read_at),
                    'created_at' => $notification->created_at?->diffForHumans() ?? 'Recently',
                    'open_url' => route('admin.notifications.open', [
                        'notification' => $notification->id,
                    ]),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'unread_count' => $admin->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    public function open(Request $request, string $notification): RedirectResponse
    {
        $admin = $this->admin();

        /** @var DatabaseNotification $item */
        $item = $admin->notifications()
            ->whereKey($notification)
            ->firstOrFail();

        if (is_null($item->read_at)) {
            $item->markAsRead();
        }

        $data = is_array($item->data) ? $item->data : [];
        $destination = $this->safeDestination(
            $request,
            $data['url'] ?? null
        );

        return redirect()->to($destination);
    }

    public function markAsRead(Request $request, string $notification): JsonResponse
    {
        $admin = $this->admin();

        /** @var DatabaseNotification $item */
        $item = $admin->notifications()
            ->whereKey($notification)
            ->firstOrFail();

        if (is_null($item->read_at)) {
            $item->markAsRead();
        }

        return response()->json([
            'success' => true,
            'unread_count' => $admin->unreadNotifications()->count(),
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $admin = $this->admin();

        $admin->unreadNotifications()->update([
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    private function admin(): object
    {
        $admin = Auth::guard('admin')->user();

        abort_unless($admin, 401, 'Admin authentication is required.');

        return $admin;
    }

    private function safeDestination(Request $request, mixed $destination): string
    {
        $fallback = route('admin.dashboard.index');

        if (! is_string($destination) || trim($destination) === '') {
            return $fallback;
        }

        $destination = trim($destination);
        $host = parse_url($destination, PHP_URL_HOST);

        if ($host !== null && strcasecmp($host, $request->getHost()) !== 0) {
            return $fallback;
        }

        $path = '/' . ltrim((string) parse_url($destination, PHP_URL_PATH), '/');
        $queryString = (string) parse_url($destination, PHP_URL_QUERY);
        parse_str($queryString, $query);

        // Repair destinations stored by the earlier notification version.
        // This also respects a custom ADMIN_PATH because named routes generate it.
        $legacyRouteMap = [
            '/incident-reports' => 'admin.blotter.index',
            '/blotter' => 'admin.blotter.index',
            '/clearance' => 'admin.clearance.index',
            '/barangay-clearance' => 'admin.clearance.index',
            '/indigency' => 'admin.indigency.index',
            '/residency' => 'admin.residency.index',
            '/residents' => 'admin.residents.index',
        ];

        foreach ($legacyRouteMap as $ending => $routeName) {
            if (str_ends_with($path, $ending)) {
                return route($routeName, $query);
            }
        }

        if (str_starts_with($destination, '/')) {
            return url($destination);
        }

        return $destination;
    }
}
