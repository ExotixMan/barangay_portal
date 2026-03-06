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

class AdminPermissionController extends Controller
{
    public function index()
    {
        $permissions = AdminPermission::all()->groupBy('module');
        $roles = AdminRole::with('permissions')->get();

        return view('admin.permissions.index', compact('permissions', 'roles'));
    }

    public function updateRolePermissions(Request $request, $roleId)
    {
        $role = AdminRole::findOrFail($roleId);

        // Prevent modifying super admin permissions
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
            // Sync permissions using the pivot table
            $role->permissions()->sync($request->permissions ?? []);

            // Log activity
            AdminActivityLog::create([
                'user_id' => Auth::id(),
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

            DB::commit();

            return redirect()->route('users.index', ['tab' => 'permissions', 'role_id' => $roleId])
                ->with('success', 'Role permissions updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update permissions: ' . $e->getMessage());
        }
    }

    public function resetToDefault(Request $request)
    {
        DB::beginTransaction();

        try {
            // Define default permissions for each role based on your CSV data
            $defaultPermissions = [
                'super_admin' => AdminPermission::pluck('id')->toArray(),
                'barangay_captain' => AdminPermission::whereIn('name', [
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
                ])->pluck('id')->toArray(),
                
                'barangay_secretary' => AdminPermission::whereIn('name', [
                    'view_dashboard',
                    'view_residents', 'create_residents', 'update_residents',
                    'view_residency', 'approve_residency', 'generate_residency_document',
                    'view_indigency', 'approve_indigency', 'generate_indigency_document',
                    'view_clearance', 'approve_clearance', 'generate_clearance_document',
                    'view_blotter', 'create_blotter',
                    'view_announcements', 'create_announcements',
                    'view_events', 'create_events'
                ])->pluck('id')->toArray(),
                
                'barangay_treasurer' => AdminPermission::whereIn('name', [
                    'view_dashboard',
                    'view_residents',
                    'view_residency',
                    'view_indigency',
                    'view_clearance',
                    'view_requests',
                    'send_request_email', 'send_request_sms',
                    'send_email', 'send_sms'
                ])->pluck('id')->toArray(),
                
                'barangay_kagawad' => AdminPermission::whereIn('name', [
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
                ])->pluck('id')->toArray(),
                
                'staff' => AdminPermission::whereIn('name', [
                    'view_residents', 'create_residents', 'update_residents',
                    'view_residency', 'view_indigency', 'view_clearance',
                    'view_blotter', 'create_blotter',
                    'view_announcements', 'view_events', 'view_projects'
                ])->pluck('id')->toArray(),
                
                'records_officer' => AdminPermission::whereIn('name', [
                    'view_residents', 'create_residents', 'update_residents', 'delete_residents',
                    'view_residency', 'view_indigency', 'view_clearance',
                    'view_blotter', 'view_announcements', 'view_events', 'view_projects'
                ])->pluck('id')->toArray(),
                
                'certificate_encoder' => AdminPermission::whereIn('name', [
                    'view_residency', 'approve_residency', 'reject_residency', 'generate_residency_document',
                    'view_indigency', 'approve_indigency', 'reject_indigency', 'generate_indigency_document',
                    'view_clearance', 'approve_clearance', 'reject_clearance', 'generate_clearance_document'
                ])->pluck('id')->toArray(),
                
                'complaints_officer' => AdminPermission::whereIn('name', [
                    'view_blotter', 'create_blotter', 'update_blotter', 'delete_blotter',
                    'add_witness', 'delete_witness'
                ])->pluck('id')->toArray(),
                
                'viewer' => AdminPermission::whereIn('name', [
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
                ])->pluck('id')->toArray(),
            ];

            foreach ($defaultPermissions as $roleName => $permissionIds) {
                $role = AdminRole::where('name', $roleName)->first();
                if ($role) {
                    $role->permissions()->sync($permissionIds);
                }
            }

            // Log activity
            AdminActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'reset_permissions',
                'module' => 'permissions',
                'details' => ['reset_to_default' => true],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return redirect()->route('users.index', ['tab' => 'permissions'])
                ->with('success', 'Permissions reset to default successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to reset permissions: ' . $e->getMessage());
        }
    }
    
    private function seedDefaultPermissions()
    {
        // Define default permissions for each role
        $defaultPermissions = [
            'super_admin' => AdminPermission::pluck('id')->toArray(),
            'admin' => AdminPermission::whereIn('name', [
                'view_residents', 'add_residents', 'edit_residents',
                'view_requests', 'create_requests', 'approve_requests',
                'view_reports', 'create_reports',
                'manage_content'
            ])->pluck('id')->toArray(),
            'staff' => AdminPermission::whereIn('name', [
                'view_residents', 'view_requests', 'create_requests',
                'view_reports'
            ])->pluck('id')->toArray(),
            'secretary' => AdminPermission::whereIn('name', [
                'view_residents', 'view_requests', 'create_requests',
                'manage_content'
            ])->pluck('id')->toArray(),
        ];

        foreach ($defaultPermissions as $roleName => $permissionIds) {
            $role = AdminRole::where('name', $roleName)->first();
            if ($role) {
                $role->permissions()->sync($permissionIds);
            }
        }
    }
}