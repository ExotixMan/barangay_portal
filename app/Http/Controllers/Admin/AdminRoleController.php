<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use App\Models\AdminPermission;
use App\Models\AdminActivityLog;
use App\Models\AdminUser;
use Illuminate\Http\Request;    
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminRoleController extends Controller
{
    public function index()
    {
        $roles = AdminRole::withCount('users')->with('permissions')->get();
        $permissions = AdminPermission::all()->groupBy('module');

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:admin_roles,name', 'max:255', 'regex:/^[a-z_]+$/'],
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:admin_permissions,id',
        ], [
            'name.regex' => 'Role name must use lowercase letters and underscores only.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('form_type', 'add_role');
        }

        DB::beginTransaction();

        try {
            $role = AdminRole::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'is_system_role' => false,
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            // Log activity
            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'create_role',
                'module' => 'roles',
                'details' => ['role_id' => $role->id, 'role_name' => $role->name],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return redirect()->route('admin.users.index', ['tab' => 'roles'])
                ->with('success', 'Role created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create role.')
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $role = AdminRole::findOrFail($id);

        // Prevent editing system roles
        if ($role->is_system_role) {
            return redirect()->back()
                ->with('error', 'System roles cannot be edited.');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:admin_roles,name,' . $id, 'regex:/^[a-z_]+$/'],
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:admin_permissions,id',
        ], [
            'name.regex' => 'Role name must use lowercase letters and underscores only.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $role->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            $role->permissions()->sync($request->permissions ?? []);

            // Log activity
            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'update_role',
                'module' => 'roles',
                'details' => ['role_id' => $role->id],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return redirect()->route('admin.users.index', ['tab' => 'roles'])
                ->with('success', 'Role updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update role.');
        }
    }

    public function destroy(Request $request, $id)
    {
        $role = AdminRole::findOrFail($id);

        // Prevent deleting system roles
        if ($role->is_system_role) {
            return redirect()->back()
                ->with('error', 'System roles cannot be deleted.');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete role with assigned users.');
        }

        DB::beginTransaction();

        try {
            // Log before deletion
            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'delete_role',
                'module' => 'roles',
                'details' => ['role_id' => $role->id, 'role_name' => $role->name],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $role->delete();

            DB::commit();

            return redirect()->route('admin.users.index', ['tab' => 'roles'])
                ->with('success', 'Role deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete role.');
        }
    }

    public function addMember(Request $request, $roleId)
    {
        $role = AdminRole::findOrFail($roleId);
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:admin_users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $user = AdminUser::findOrFail($request->user_id);
            $user->update(['role_id' => $roleId]);

            // Log activity
            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'add_user_to_role',
                'module' => 'roles',
                'details' => [
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'user_id' => $user->id,
                    'user_name' => $user->full_name
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return redirect()->route('admin.users.index', ['tab' => 'roles'])
                ->with('success', 'User added to role successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to add user to role.');
        }
    }

    public function removeMember(Request $request, $roleId, $userId)
    {
        $role = AdminRole::findOrFail($roleId);
        $user = AdminUser::findOrFail($userId);

        DB::beginTransaction();

        try {
            // Remove user from role (set to default role or null)
            // You might want to set a default role like 'staff'
            $user->update(['role_id' => null]); // or set to default role ID

            // Log activity
            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'remove_user_from_role',
                'module' => 'roles',
                'details' => [
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'user_id' => $user->id,
                    'user_name' => $user->full_name
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return redirect()->route('admin.users.index', ['tab' => 'roles'])
                ->with('success', 'User removed from role successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to remove user from role.');
        }
    }

    public function getPermissions(Request $request, $id)
    {
        $role = AdminRole::with('permissions')->findOrFail($id);

        AdminActivityLog::create([
            'user_id' => Auth::guard('admin')->id(),
            'action' => 'VIEW_ROLE_PERMISSIONS',
            'module' => 'roles',
            'details' => [
                'role_id' => $role->id,
                'role_name' => $role->name,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'success' => true,
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
            ],
            'permissions' => $role->permissions->pluck('id'),
        ]);
    }

    public function updatePermissions(Request $request, $id)
    {
        $role = AdminRole::findOrFail($id);

        // Prevent modifying system role permissions.
        if ($role->is_system_role || $role->name === 'super_admin') {
            return redirect()->back()
                ->with('error', 'System role permissions cannot be modified.');
        }

        $validator = Validator::make($request->all(), [
            'permissions' => 'nullable|array',
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

            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'update_role_permissions',
                'module' => 'roles',
                'details' => [
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'permission_count' => count($request->permissions ?? []),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return redirect()->route('admin.users.index', [
                'tab' => 'permissions',
                'selected_role' => $role->id,
            ])->with('success', 'Role permissions updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update role permissions.');
        }
    }
}