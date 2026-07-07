<?php

namespace App\Providers;

use App\Models\NotificationLog;
use App\Services\Chatbot\ClaudeAIEngine;
use App\Services\Chatbot\HybridAgent;
use App\Services\Chatbot\InputGuard;
use App\Services\Chatbot\OutputValidator;
use App\Services\Chatbot\RuleBasedEngine;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Events\QueryExecuted;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(InputGuard::class);
        $this->app->singleton(OutputValidator::class);
        $this->app->singleton(RuleBasedEngine::class);
        $this->app->singleton(ClaudeAIEngine::class);

        $this->app->singleton(HybridAgent::class, function ($app) {
            return new HybridAgent(
                $app->make(InputGuard::class),
                $app->make(OutputValidator::class),
                $app->make(RuleBasedEngine::class),
                $app->make(ClaudeAIEngine::class),
            );
        });
    }

    public function boot(): void
    {
        DB::listen(function (QueryExecuted $query) {
            if ($query->time > 1000) { // more than 1 second
                Log::warning('Slow query', [
                    'sql' => $query->toRawSql(),
                    'time_ms' => $query->time,
                ]);
            }
        });

        $this->registerBladeDirectives();
        $this->registerEmailLogger();

        Paginator::useBootstrapFive();

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }

    private function registerEmailLogger(): void
    {
        Event::listen(MessageSent::class, function (MessageSent $event) {
            try {
                if (!Schema::hasTable('notification_logs')) {
                    Log::warning('Email was sent but notification_logs table does not exist.');
                    return;
                }

                $originalMessage = $event->sent->getOriginalMessage();

                $to = null;
                $from = null;
                $subject = 'Email Sent';

                if ($originalMessage instanceof Email) {
                    $to = collect($originalMessage->getTo() ?? [])
                        ->map(fn ($address) => $address->getAddress())
                        ->filter()
                        ->implode(', ');

                    $from = collect($originalMessage->getFrom() ?? [])
                        ->map(fn ($address) => $address->getAddress())
                        ->filter()
                        ->implode(', ');

                    $subject = $originalMessage->getSubject() ?: 'Email Sent';
                }

                DB::table('notification_logs')->insert([
                    'channel' => 'email',
                    'status' => 'sent',
                    'recipient' => $to,
                    'subject' => $subject,
                    'message' => null,
                    'error_message' => null,
                    'reference_type' => null,
                    'reference_id' => null,
                    'sent_by' => Auth::guard('admin')->check()
                        ? Auth::guard('admin')->id()
                        : null,
                    'sent_at' => now('Asia/Manila'),
                    'metadata' => json_encode([
                        'from' => $from,
                        'source' => 'message_sent_event',
                    ]),
                    'created_at' => now('Asia/Manila'),
                    'updated_at' => now('Asia/Manila'),
                ]);
            } catch (Throwable $e) {
                Log::error('Email was sent but notification log failed.', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        });
    }

    private function registerBladeDirectives(): void
    {
        Blade::if('admin_can', function ($permission) {
            /** @var \App\Models\AdminUser|null $user */
            $user = Auth::guard('admin')->user();

            if (!$user) {
                return false;
            }

            if ($user->isSuperAdmin()) {
                return true;
            }

            return $user->hasPermission($permission);
        });

        Blade::if('admin_canModule', function ($module) {
            /** @var \App\Models\AdminUser|null $user */
            $user = Auth::guard('admin')->user();

            if (!$user) {
                return false;
            }

            if ($user->isSuperAdmin()) {
                return true;
            }

            return $user->hasModuleAccess($module);
        });

        Blade::if('admin_role', function ($role) {
            /** @var \App\Models\AdminUser|null $user */
            $user = Auth::guard('admin')->user();

            if (!$user || !$user->role) {
                return false;
            }

            if ($user->isSuperAdmin()) {
                return true;
            }

            return $user->role->name === $role;
        });

        Blade::if('superadmin', function () {
            /** @var \App\Models\AdminUser|null $user */
            $user = Auth::guard('admin')->user();

            return $user && $user->isSuperAdmin();
        });
    }
}