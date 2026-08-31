<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminPermissionController extends Controller
{
    private const LOOKUP_CACHE_SECONDS = 300;

    private function getRolesWithPermissions()
    {
        return Cache::remember('admin_user_role.roles_with_permissions_count', now()->addSeconds(self::LOOKUP_CACHE_SECONDS), function () {
            return AdminRole::with('permissions')
                ->withCount('users')
                ->get();
        });
    }

    private function getGroupedPermissions()
    {
        return Cache::remember('admin_user_role.permissions_grouped', now()->addSeconds(self::LOOKUP_CACHE_SECONDS), function () {
            return AdminPermission::orderBy('module')
                ->orderBy('name')
                ->get()
                ->groupBy('module');
        });
    }

    private function clearUserRoleLookupCache(): void
    {
        Cache::forget('admin_user_role.roles_with_permissions_count');
        Cache::forget('admin_user_role.permissions_grouped');
        Cache::forget('admin_user_role.audit_actions');
        Cache::forget('admin_user_role.audit_modules');
    }

    public function index()
    {
        $permissions = $this->getGroupedPermissions();
        $roles = $this->getRolesWithPermissions();

        return view('admin.permissions.index', compact('permissions', 'roles'));
    }

    public function updateRolePermissions(Request $request,int $roleId)
    {
        $role = AdminRole::findOrFail($roleId);
        $adminId = Auth::guard('admin')->id();

        if ($role->name === 'super_admin') {
            return redirect()->back()
                ->with('error', 'Super Admin permissions cannot be modified.');
        }

        $validator = Validator::make($request->all(), [
            'permissions' => 'array',
            'permissions.*' => 'exists:admin_permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            
            $role->permissions()->sync($request->permissions ?? []);

            if ($adminId) {
                AdminActivityLog::create([
                    'user_id' => $adminId,
                    'action' => 'update_role_permissions',
                    'module' => 'permissions',
                    'details' => [
                        'role_id' => $role->id,
                        'role_name' => $role->name,
                        'permissions' => $request->permissions
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            }

            DB::commit();
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index', ['tab' => 'permissions', 'role_id' => $roleId])
                ->with('success', 'Role permissions updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update permissions.');
        }
    }

    public function resetToDefault(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        DB::beginTransaction();

        try {
            $permissionsByName = AdminPermission::pluck('id', 'name');
            $permissionIdsFor = function (array $names) use ($permissionsByName) {
                return collect($names)
                    ->map(fn ($name) => $permissionsByName->get($name))
                    ->filter()
                    ->values()
                    ->all();
            };

            
            $defaultPermissions = [
                'super_admin' => $permissionsByName->values()->all(),
                'barangay_captain' => $permissionIdsFor([
                    'view_dashboard', 'view_forecast',
                    'view_residents', 'create_residents', 'update_residents',
                    'view_residency', 'approve_residency',
                    'view_indigency', 'approve_indigency',
                    'view_clearance', 'approve_clearance',
                    'view_blotter', 'create_blotter',
                    'view_announcements', 'create_announcements',
                    'view_events', 'create_events',
                    'view_projects', 'create_projects',
                    'view_requests', 'update_requests'
                ]),

                'barangay_secretary' => $permissionIdsFor([
                    'view_dashboard',
                    'view_residents', 'create_residents', 'update_residents',
                    'view_residency', 'approve_residency', 'generate_residency_document',
                    'view_indigency', 'approve_indigency', 'generate_indigency_document',
                    'view_clearance', 'approve_clearance', 'generate_clearance_document',
                    'view_blotter', 'create_blotter',
                    'view_announcements', 'create_announcements',
                    'view_events', 'create_events'
                ]),

                'barangay_treasurer' => $permissionIdsFor([
                    'view_dashboard',
                    'view_residents',
                    'view_residency',
                    'view_indigency',
                    'view_clearance',
                    'view_requests',
                    'send_request_email', 'send_request_sms',
                    'send_email', 'send_sms'
                ]),

                'barangay_kagawad' => $permissionIdsFor([
                    'view_dashboard', 'view_forecast',
                    'view_residents',
                    'view_residency',
                    'view_indigency',
                    'view_clearance',
                    'view_blotter',
                    'view_announcements',
                    'view_events',
                    'view_projects',
                    'view_requests',
                    'view_users',
                    'view_roles',
                    'view_permissions'
                ]),

                'staff' => $permissionIdsFor([
                    'view_residents', 'create_residents', 'update_residents',
                    'view_residency', 'view_indigency', 'view_clearance',
                    'view_blotter', 'create_blotter',
                    'view_announcements', 'view_events', 'view_projects'
                ]),

                'records_officer' => $permissionIdsFor([
                    'view_residents', 'create_residents', 'update_residents', 'delete_residents',
                    'view_residency', 'view_indigency', 'view_clearance',
                    'view_blotter', 'view_announcements', 'view_events', 'view_projects'
                ]),

                'certificate_encoder' => $permissionIdsFor([
                    'view_residency', 'approve_residency', 'reject_residency', 'generate_residency_document',
                    'view_indigency', 'approve_indigency', 'reject_indigency', 'generate_indigency_document',
                    'view_clearance', 'approve_clearance', 'reject_clearance', 'generate_clearance_document'
                ]),

                'complaints_officer' => $permissionIdsFor([
                    'view_blotter', 'create_blotter', 'update_blotter', 'delete_blotter',
                    'add_witness', 'delete_witness'
                ]),

                'viewer' => $permissionIdsFor([
                    'view_dashboard', 'view_forecast',
                    'view_residents',
                    'view_residency',
                    'view_indigency',
                    'view_clearance',
                    'view_blotter',
                    'view_announcements',
                    'view_events',
                    'view_projects',
                    'view_requests',
                    'view_users',
                    'view_roles',
                    'view_permissions'
                ]),
            ];

            $rolesByName = AdminRole::whereIn('name', array_keys($defaultPermissions))
                ->get()
                ->keyBy('name');

            foreach ($defaultPermissions as $roleName => $permissionIds) {
                $role = $rolesByName->get($roleName);
                if ($role) {
                    $role->permissions()->sync($permissionIds);
                }
            }

            if ($adminId) {
                AdminActivityLog::create([
                    'user_id' => $adminId,
                    'action' => 'reset_permissions',
                    'module' => 'permissions',
                    'details' => ['reset_to_default' => 'true'],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            }

            DB::commit();
            $this->clearUserRoleLookupCache();

            $roleId = $request->input('role_id');

            return redirect()->route('admin.users.index', array_filter([
                'tab' => 'permissions',
                'role_id' => $roleId,
            ]))
                ->with('success', 'Permissions reset to default successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to reset permissions.');
        }
    }
    
    private function seedDefaultPermissions()
    {
        
        $permissionsByName = AdminPermission::pluck('id', 'name');
        $permissionIdsFor = function (array $names) use ($permissionsByName) {
            return collect($names)
                ->map(fn ($name) => $permissionsByName->get($name))
                ->filter()
                ->values()
                ->all();
        };

        $defaultPermissions = [
            'super_admin' => $permissionsByName->values()->all(),
            'admin' => $permissionIdsFor([
                'view_residents', 'add_residents', 'edit_residents',
                'view_requests', 'create_requests', 'approve_requests',
                'view_reports', 'create_reports',
                'manage_content'
            ]),
            'staff' => $permissionIdsFor([
                'view_residents', 'view_requests', 'create_requests',
                'view_reports'
            ]),
            'secretary' => $permissionIdsFor([
                'view_residents', 'view_requests', 'create_requests',
                'manage_content'
            ]),
        ];

        $rolesByName = AdminRole::whereIn('name', array_keys($defaultPermissions))
            ->get()
            ->keyBy('name');

        foreach ($defaultPermissions as $roleName => $permissionIds) {
            $role = $rolesByName->get($roleName);
            if ($role) {
                $role->permissions()->sync($permissionIds);
            }
        }
    }
}