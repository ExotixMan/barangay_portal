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
use Illuminate\Support\Facades\Cache;

class AdminRoleController extends Controller
{
    private const LOOKUP_CACHE_SECONDS = 300;

    private function getRolesWithPermissions()
    {
        return Cache::remember('admin_user_role.roles_with_permissions_count', now()->addSeconds(self::LOOKUP_CACHE_SECONDS), function () {
            return AdminRole::withCount('users')
                ->with('permissions')
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
        $roles = $this->getRolesWithPermissions();
        $permissions = $this->getGroupedPermissions();

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
            
            $roleId = DB::table('admin_roles')->insertGetId([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'is_system_role' => DB::raw("'f'::boolean"),  
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $role = AdminRole::findOrFail($roleId);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'create_role',
                'module' => 'roles',
                'details' => ['role_id' => $role->id, 'role_name' => $role->name],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index', ['tab' => 'roles'])
                ->with('success', 'Role created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create role.')
                ->withInput();
        }
    }

    public function update(Request $request,int $id)
    {
        $role = AdminRole::findOrFail($id);

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
                ->withInput()
                ->with('form_type', 'edit_role_' . $id);
        }

        DB::beginTransaction();

        try {
            $role->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            $role->permissions()->sync($request->permissions ?? []);

            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'update_role',
                'module' => 'roles',
                'details' => ['role_id' => $role->id],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index', ['tab' => 'roles'])
                ->with('success', 'Role updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update role.');
        }
    }

    public function destroy(Request $request,int $id)
    {
        $role = AdminRole::findOrFail($id);

        if ($role->is_system_role) {
            return redirect()->back()
                ->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete role with assigned users.');
        }

        DB::beginTransaction();

        try {
            
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
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index', ['tab' => 'roles'])
                ->with('success', 'Role deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete role.');
        }
    }

    public function addMember(Request $request,int $roleId)
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
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index', ['tab' => 'roles'])
                ->with('success', 'User added to role successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to add user to role.');
        }
    }

    public function removeMember(Request $request, int $roleId, int $userId)
    {
        $role = AdminRole::findOrFail($roleId);
        $user = AdminUser::findOrFail($userId);

        DB::beginTransaction();

        try {

            $user->update(['role_id' => null]); 

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
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index', ['tab' => 'roles'])
                ->with('success', 'User removed from role successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to remove user from role.');
        }
    }

    public function getPermissions(Request $request,int $id)
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
        $this->clearUserRoleLookupCache();

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

    public function updatePermissions(Request $request,int $id)
    {
        $role = AdminRole::findOrFail($id);

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
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index', [
                'tab' => 'permissions',
                'role_id' => $role->id,
            ])->with('success', 'Role permissions updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update role permissions.');
        }
    }
}