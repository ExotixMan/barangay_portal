<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use App\Services\Chatbot\ClaudeAIEngine;
use App\Services\Chatbot\HybridAgent;
use App\Services\Chatbot\InputGuard;
use App\Services\Chatbot\OutputValidator;
use App\Services\Chatbot\RuleBasedEngine;

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
        $this->registerBladeDirectives();
        Paginator::useBootstrapFive();
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }

    private function registerBladeDirectives()
    {
        // Admin permission directive
        Blade::if('admin_can', function ($permission) {
            /** @var \App\Models\AdminUser|null $user */
            $user = Auth::guard('admin')->user();
            
            if (!$user) {
                return false;
            }
            
            // SUPERADMIN CHECK - This is the key!
            if ($user->isSuperAdmin()) {
                return true;
            }
            
            return $user->hasPermission($permission);
        });

        // Admin module access directive
        Blade::if('admin_canModule', function ($module) {
            /** @var \App\Models\AdminUser|null $user */
            $user = Auth::guard('admin')->user();
            
            if (!$user) {
                return false;
            }
            
            // SUPERADMIN CHECK
            if ($user->isSuperAdmin()) {
                return true;
            }
            
            return $user->hasModuleAccess($module);
        });

        // Admin role directive
        Blade::if('admin_role', function ($role) {
            /** @var \App\Models\AdminUser|null $user */
            $user = Auth::guard('admin')->user();
            
            if (!$user || !$user->role) {
                return false;
            }
            
            // SUPERADMIN CHECK - Superadmin should have access to everything
            if ($user->isSuperAdmin()) {
                return true;
            }
            
            return $user->role->name === $role;
        });

        // Super admin check
        Blade::if('superadmin', function () {
            /** @var \App\Models\AdminUser|null $user */
            $user = Auth::guard('admin')->user();
            
            return $user && $user->isSuperAdmin();
        });
    }
}