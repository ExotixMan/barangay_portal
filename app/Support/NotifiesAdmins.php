<?php

namespace App\Support;

use App\Notifications\NewAdminSubmissionNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Throwable;

trait NotifiesAdmins
{
    protected function notifyAdminsOfSubmission(
        string $category,
        string $title,
        string $message,
        string $url,
        string $icon = 'fas fa-bell',
    ): void {
        try {
            $provider = config('auth.guards.admin.provider');
            $adminModelClass = is_string($provider)
                ? config("auth.providers.{$provider}.model")
                : null;

            if (! is_string($adminModelClass) || ! class_exists($adminModelClass)) {
                throw new RuntimeException(
                    'The admin authentication provider model is not configured in config/auth.php.'
                );
            }

            $adminModel = new $adminModelClass();
            $query = $adminModelClass::query();

            if (Schema::hasColumn($adminModel->getTable(), 'status')) {
                $query->where(function ($statusQuery) {
                    $statusQuery
                        ->whereNull('status')
                        ->orWhere('status', 'active');
                });
            }

            $admins = $query->get();

            if ($admins->isEmpty()) {
                return;
            }

            Notification::send(
                $admins,
                new NewAdminSubmissionNotification(
                    category: $category,
                    title: $title,
                    message: $message,
                    url: $url,
                    icon: $icon,
                )
            );
        } catch (Throwable $e) {
            
            report($e);
        }
    }
}
