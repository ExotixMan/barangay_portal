<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\AdminPermission;
use App\Models\AdminUser;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        
    ];

    public function register(): void
    {
        
    }

    public function boot(): void
    {
        $this->registerPermissions();
    }

    private function registerPermissions()
    {
        try {
            
            $permissions = AdminPermission::all();

            foreach ($permissions as $permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    
                    if (!$user || !($user instanceof AdminUser)) {
                        return false;
                    }

                    if ($user->role && $user->role->name === 'super_admin') {
                        return true;
                    }

                    return $user->role && $user->role->permissions()
                        ->where('permission_id', $permission->id)
                        ->exists();
                });
            }

            Gate::define('is-super-admin', function ($user) {
                return $user && $user instanceof AdminUser && $user->role && $user->role->name === 'super_admin';
            });

            Gate::define('is-barangay-captain', function ($user) {
                return $user && $user instanceof AdminUser && $user->role && $user->role->name === 'barangay_captain';
            });

            Gate::define('is-barangay-secretary', function ($user) {
                return $user && $user instanceof AdminUser && $user->role && $user->role->name === 'barangay_secretary';
            });

            Gate::define('is-barangay-treasurer', function ($user) {
                return $user && $user instanceof AdminUser && $user->role && $user->role->name === 'barangay_treasurer';
            });

            Gate::define('is-barangay-kagawad', function ($user) {
                return $user && $user instanceof AdminUser && $user->role && $user->role->name === 'barangay_kagawad';
            });

            Gate::define('is-staff', function ($user) {
                return $user && $user instanceof AdminUser && $user->role && $user->role->name === 'staff';
            });

            Gate::define('is-records-officer', function ($user) {
                return $user && $user instanceof AdminUser && $user->role && $user->role->name === 'records_officer';
            });

            Gate::define('is-certificate-encoder', function ($user) {
                return $user && $user instanceof AdminUser && $user->role && $user->role->name === 'certificate_encoder';
            });

            Gate::define('is-complaints-officer', function ($user) {
                return $user && $user instanceof AdminUser && $user->role && $user->role->name === 'complaints_officer';
            });

            Gate::define('is-viewer', function ($user) {
                return $user && $user instanceof AdminUser && $user->role && $user->role->name === 'viewer';
            });

            Gate::define('access-residents-module', function ($user) {
                if (!$user || !($user instanceof AdminUser)) return false;
                return $user->hasAnyPermission([
                    'view_residents', 'create_residents', 'update_residents', 
                    'delete_residents', 'restore_residents', 'export_residents', 'bulk_delete_residents'
                ]);
            });

            Gate::define('access-blotter-module', function ($user) {
                if (!$user || !($user instanceof AdminUser)) return false;
                return $user->hasAnyPermission([
                    'view_blotter', 'create_blotter', 'update_blotter',
                    'delete_blotter', 'restore_blotter', 'export_blotter', 'add_witness', 'delete_witness'
                ]);
            });

            Gate::define('access-residency-module', function ($user) {
                if (!$user || !($user instanceof AdminUser)) return false;
                return $user->hasAnyPermission([
                    'view_residency', 'approve_residency', 'reject_residency',
                    'delete_residency', 'export_residency', 'generate_residency_document'
                ]);
            });

            Gate::define('access-indigency-module', function ($user) {
                if (!$user || !($user instanceof AdminUser)) return false;
                return $user->hasAnyPermission([
                    'view_indigency', 'approve_indigency', 'reject_indigency',
                    'delete_indigency', 'export_indigency', 'generate_indigency_document'
                ]);
            });

            Gate::define('access-clearance-module', function ($user) {
                if (!$user || !($user instanceof AdminUser)) return false;
                return $user->hasAnyPermission([
                    'view_clearance', 'approve_clearance', 'reject_clearance',
                    'delete_clearance', 'export_clearance', 'generate_clearance_document'
                ]);
            });

        } catch (\Exception $e) {
            
            return;
        }
    }
}