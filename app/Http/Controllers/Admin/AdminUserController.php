<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\AdminRole;
use App\Models\AdminPermission;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminUser::with('role');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('user_id', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get all roles with user counts using the correct relationship
        $roles = AdminRole::with('permissions')
            ->withCount('users')
            ->get();
        
        // Get all permissions grouped by module
        $allPermissions = AdminPermission::all()->groupBy('module');
        
        // Get selected role from request or default to first role
        $selectedRoleId = $request->input('role_id', $roles->first()->id ?? null);
        $selectedRole = $selectedRoleId ? AdminRole::with('permissions')->find($selectedRoleId) : null;
        
        // Get permission IDs for the selected role
        $selectedRolePermissionIds = $selectedRole ? $selectedRole->permissions->pluck('id')->toArray() : [];

        $allUsers = AdminUser::all();

        // Stats
        $total_users = AdminUser::count();
        $active_users = AdminUser::where('status', 'active')->count();
        $super_admins = AdminUser::whereHas('role', function($q) {
            $q->where('name', 'super_admin');
        })->count();
        $total_roles = AdminRole::count();
        

        return view('admin.admin_userNrole', compact(
            'users',
            'roles',
            'allPermissions',
            'selectedRole',
            'selectedRolePermissionIds',
            'allUsers',
            'total_users',
            'active_users',
            'super_admins',
            'total_roles'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin_users,email',
            'username' => 'required|string|unique:admin_users,username|min:3|max:255',
            'password' => 'required|string|min:8',
            'contact_number' => 'nullable|string|max:11',
            'role_id' => 'required|exists:admin_roles,id',
            'department' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('form_type', 'add_user');
        }

        DB::beginTransaction();

        try {
            $user = AdminUser::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'contact_number' => $request->contact_number,
                'role_id' => $request->role_id,
                'department' => $request->department,
                'status' => $request->status ?? 'active',
            ]);

            // Log activity
            AdminActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'create_user',
                'module' => 'users',
                'details' => ['user_id' => $user->id, 'user_name' => $user->full_name],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Send welcome email if checked
            if ($request->has('send_welcome_email')) {
                // sMail::to($user->email)->send(new WelcomeEmail($user, $request->password));
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create user. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $user = AdminUser::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'username' => 'required|string|unique:users,username,' . $id . '|min:3|max:255',
            'contact_number' => 'nullable|string|max:11',
            'role_id' => 'required|exists:roles,id',
            'department' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('form_type', 'edit_user_' . $id);
        }

        DB::beginTransaction();

        try {
            $oldData = $user->toArray();
            
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'contact_number' => $request->contact_number,
                'role_id' => $request->role_id,
                'department' => $request->department,
                'status' => $request->status,
            ]);

            // Log activity
            AdminActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update_user',
                'module' => 'users',
                'details' => [
                    'user_id' => $user->id,
                    'changes' => array_diff_assoc($user->toArray(), $oldData)
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update user.')
                ->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = AdminUser::findOrFail($id);

        // Prevent deleting super admin
        if ($user->role && $user->role->name === 'super_admin') {
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete super admin user.');
        }

        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        DB::beginTransaction();

        try {
            // Log before deletion
            AdminActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete_user',
                'module' => 'users',
                'details' => ['user_id' => $user->id, 'user_name' => $user->full_name],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $user->delete();

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')
                ->with('error', 'Failed to delete user.');
        }
    }

    public function resetPassword(Request $request, $id)
    {
        $user = AdminUser::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'new_password' => 'sometimes|required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('form_type', 'edit_user_' . $id);
        }

        DB::beginTransaction();

        try {
            $newPassword = $request->new_password ?? 'password123'; // Generate random password in production

            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            // Log activity
            AdminActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'reset_password',
                'module' => 'users',
                'details' => ['user_id' => $user->id],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Password reset successfully. New password: ' . $newPassword);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')
                ->with('error', 'Failed to reset password.');
        }
    }

    public function updatePermissions(Request $request, $id)
    {
        $user = AdminUser::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // For user-specific permissions (if you implement user_permissions table)
            // $user->permissions()->sync($request->permissions ?? []);

            // Log activity
            AdminActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update_user_permissions',
                'module' => 'users',
                'details' => [
                    'user_id' => $user->id,
                    'permissions' => $request->permissions
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User permissions updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')
                ->with('error', 'Failed to update permissions.');
        }
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:delete,activate,deactivate,suspend',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $users = AdminUser::whereIn('id', $request->user_ids);

            switch ($request->action) {
                case 'delete':
                    // Filter out super admins and current user
                    $users->whereDoesntHave('role', function($q) {
                        $q->where('name', 'super_admin');
                    })->where('id', '!=', Auth::id())->delete();
                    break;
                    
                case 'activate':
                    $users->update(['status' => 'active']);
                    break;
                    
                case 'deactivate':
                    $users->update(['status' => 'inactive']);
                    break;
                    
                case 'suspend':
                    $users->update(['status' => 'suspended']);
                    break;
            }

            // Log bulk action
            AdminActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'bulk_' . $request->action,
                'module' => 'users',
                'details' => ['user_ids' => $request->user_ids],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Bulk action completed successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to complete bulk action.'], 500);
        }
    }
}