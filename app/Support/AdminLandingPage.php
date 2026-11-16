<?php

namespace App\Support;

use App\Models\AdminUser;

class AdminLandingPage
{
    public static function routeFor(AdminUser $user): string
    {
        $permissionRoutes = [
            'view_forecast' => 'admin.dashboard.index',
            'view_dashboard' => 'admin.dashboard.index',
            'view_residents' => 'admin.residents.index',
            'view_residency' => 'admin.residency.index',
            'view_indigency' => 'admin.indigency.index',
            'view_clearance' => 'admin.clearance.index',
            'view_blotter' => 'admin.blotter.index',
            'view_announcements' => 'admin.announcements.index',
            'view_events' => 'admin.events.index',
            'view_projects' => 'admin.projects.index',
            'view_requests' => 'admin.request.index',
            'view_users' => 'admin.users.index',
            'view_roles' => 'admin.roles.index',
            'view_permissions' => 'admin.permissions.index',
            'manage_chatbot' => 'admin.chatbot.index',
            'system_settings' => 'admin.settings.index',
            'view_backup' => 'admin.backup.index',
            'manage_backup' => 'admin.backup.index',
            'backup_system' => 'admin.backup.index',
        ];

        foreach ($permissionRoutes as $permission => $route) {
            if ($user->hasPermission($permission)) {
                return $route;
            }
        }

        return 'admin.dashboard.index';
    }
}
