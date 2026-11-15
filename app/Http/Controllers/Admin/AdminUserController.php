<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminUserController extends Controller
{
    private const LOOKUP_CACHE_SECONDS = 300;

    private function getAuditLogsBaseQuery()
    {
        return AdminActivityLog::query()
            ->with('user')
            ->orderByDesc('created_at');
    }

    private function applyAuditFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('log_action')) {
            $query->where('action', $request->input('log_action'));
        }

        if ($request->filled('log_module')) {
            $query->where('module', $request->input('log_module'));
        }

        if ($request->filled('log_user_id')) {
            $query->where('user_id', $request->input('log_user_id'));
        }

        if ($request->filled('log_date')) {
            $date = \Illuminate\Support\Carbon::parse($request->input('log_date'));

            $query->whereBetween('created_at', [
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay(),
            ]);
        }

        return $query;
    }

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

    private function getAuditActions()
    {
        return Cache::remember('admin_user_role.audit_actions', now()->addSeconds(self::LOOKUP_CACHE_SECONDS), function () {
            $predefinedActions = [
                'CREATE',
                'READ',
                'UPDATE',
                'DELETE',
                'VIEW',
                'APPROVE',
                'REJECT',
                'EMAIL',
                'SMS',
                'EXPORT',
                'DOWNLOAD',
                'BULK_DELETE',
                'APPROVAL_STATUS_CHANGE',
            ];

            $dbActions = AdminActivityLog::select('action')
                ->distinct()
                ->pluck('action')
                ->toArray();

            return collect(array_unique(array_merge($predefinedActions, $dbActions)))
                ->sort()
                ->values();
        });
    }

    private function getAuditModules()
    {
        return Cache::remember('admin_user_role.audit_modules', now()->addSeconds(self::LOOKUP_CACHE_SECONDS), function () {
            return AdminActivityLog::select('module')
                ->distinct()
                ->orderBy('module')
                ->pluck('module');
        });
    }

    private function clearUserRoleLookupCache(): void
    {
        Cache::forget('admin_user_role.roles_with_permissions_count');
        Cache::forget('admin_user_role.permissions_grouped');
        Cache::forget('admin_user_role.audit_actions');
        Cache::forget('admin_user_role.audit_modules');
    }

    public function index(Request $request)
    {
        AdminActivityLog::create([
            'user_id' => Auth::guard('admin')->id(),
            'action' => 'VIEW',
            'module' => 'Users & Roles',
            'details' => [
                'tab' => $request->input('tab', 'users'),
                'search' => $request->input('search'),
                'role_filter' => $request->input('role'),
                'status_filter' => $request->input('status'),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $query = AdminUser::with(['role.permissions', 'permissions']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_initial', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('user_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        $roles = $this->getRolesWithPermissions();
        $allPermissions = $this->getGroupedPermissions();

        $selectedRoleId = $request->input('role_id')
            ?? $request->input('selected_role')
            ?? ($roles->first()->id ?? null);
        $selectedRole = $selectedRoleId ? $roles->firstWhere('id', (int) $selectedRoleId) : null;

        if (! $selectedRole && $selectedRoleId) {
            $selectedRole = AdminRole::with('permissions')->find($selectedRoleId);
        }

        $selectedRolePermissionIds = $selectedRole ? $selectedRole->permissions->pluck('id')->toArray() : [];

        $allUsers = AdminUser::query()
            ->select([
                'id',
                'user_id',
                'first_name',
                'middle_initial',
                'last_name',
                'email',
                'username',
                'role_id',
                'department',
                'status',
            ])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        // The member dialogs and selectors reuse this one directory query.
        $membersByRole = $allUsers->groupBy('role_id');
        foreach ($roles as $role) {
            $role->setRelation('users', $membersByRole->get($role->id, new \Illuminate\Database\Eloquent\Collection));
        }

        $userStats = AdminUser::query()
            ->selectRaw('COUNT(*) as total_users')
            ->selectRaw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users")
            ->first();

        $total_users = (int) ($userStats->total_users ?? 0);
        $active_users = (int) ($userStats->active_users ?? 0);
        $super_admins = AdminUser::whereHas('role', function ($q) {
            $q->where('name', 'super_admin');
        })->count();
        $total_roles = $roles->count();

        $auditLogsQuery = $this->applyAuditFilters($this->getAuditLogsBaseQuery(), $request);
        $auditLogs = $auditLogsQuery->paginate(10, ['*'], 'logs_page');

        $auditActions = $this->getAuditActions();
        $auditModules = $this->getAuditModules();

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
            'total_roles',
            'auditLogs',
            'auditActions',
            'auditModules'
        ));
    }

    public function exportAuditLogsCsv(Request $request): StreamedResponse
    {
        $fileName = 'admin_user_role_audit_logs_'.now()->format('Ymd_His').'.csv';

        $logsQuery = $this->applyAuditFilters($this->getAuditLogsBaseQuery(), $request);

        return response()->streamDownload(function () use ($logsQuery) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Date/Time', 'User', 'Action', 'Module', 'Details', 'IP Address', 'User Agent']);

            $logsQuery->chunk(500, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    $details = is_array($log->details) ? $log->details : (json_decode((string) $log->details, true) ?: $log->details);

                    fputcsv($handle, [
                        optional($log->created_at)->format('Y-m-d H:i:s'),
                        $log->user?->full_name ?? 'Unknown User',
                        $log->action,
                        $log->module,
                        is_array($details) ? json_encode($details) : (string) $details,
                        $log->ip_address,
                        $log->user_agent,
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function dialog(string $type, int $id)
    {
        $actor = Auth::guard('admin')->user();
        $allPermissions = $this->getGroupedPermissions();
        if ($type === 'user-permissions') {
            abort_unless($actor->hasPermission('manage_user_permissions'), 403);
            $user = AdminUser::with(['role.permissions', 'permissions'])->findOrFail($id);

            return response()->view('admin.partials.user-permissions-dialog', compact('user', 'allPermissions'))
                ->header('Cache-Control', 'private, no-store');
        }
        abort_unless($type === 'role-edit' && $actor->hasPermission('update_roles'), 403);
        $role = AdminRole::with('permissions')->findOrFail($id);

        return response()->view('admin.partials.role-edit-dialog', compact('role', 'allPermissions'))
            ->header('Cache-Control', 'private, no-store');
    }

    public function store(Request $request)
    {
        $request->merge([
            'contact_number' => preg_replace('/\D+/', '', (string) $request->input('contact_number')),
        ]);

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'min:2', 'max:255', "regex:/^[\p{L}\p{M}\s.'-]+$/u"],
            'middle_initial' => ['nullable', 'string', 'max:3', "regex:/^\p{L}\p{M}*\\.?$/u"],
            'last_name' => ['required', 'string', 'min:2', 'max:255', "regex:/^[\p{L}\p{M}\s.'-]+$/u"],
            'email' => 'required|email|unique:admin_users,email',
            'username' => ['required', 'string', 'unique:admin_users,username', 'min:3', 'max:255', 'regex:/^[a-zA-Z0-9_.\-]+$/'],
            'password' => ['required', 'regex:/^\S+$/', PasswordRules::min(8)->mixedCase()->numbers()->symbols()],
            'contact_number' => ['nullable', 'regex:/^09\d{9}$/'],
            'role_id' => 'required|exists:admin_roles,id',
            'department' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,suspended',
        ], [
            'first_name.regex' => 'First name can only contain letters, spaces, apostrophes, hyphens, and periods.',
            'middle_initial.regex' => 'Middle initial must be a single letter, with an optional period (e.g. A or A.).',
            'last_name.regex' => 'Last name can only contain letters, spaces, apostrophes, hyphens, and periods.',
            'username.regex' => 'Username can only contain letters, numbers, underscores, dots, and hyphens.',
            'contact_number.regex' => 'Contact number must be in the format 09XXXXXXXXX.',
            'password.regex' => 'Password cannot contain spaces.',
            'password.*' => 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation', 'new_password']))
                ->with('form_type', 'add_user');
        }

        app(\App\Services\AdminAccountProtection::class)->authorize(roleId: (int) $request->role_id);
        DB::beginTransaction();

        try {
            $user = AdminUser::create([
                'first_name' => $request->first_name,
                'middle_initial' => $request->middle_initial ? mb_strtoupper($request->middle_initial) : null,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'contact_number' => $request->contact_number,
                'role_id' => $request->role_id,
                'department' => $request->department,
                'status' => $request->status ?? 'active',
            ]);

            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'CREATE',
                'module' => 'Users',
                'details' => json_encode(['user_id' => $user->id, 'user_name' => $user->first_name.' '.$user->last_name]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($request->has('send_welcome_email')) {

            }

            DB::commit();
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to create user.')
                ->withInput($request->except(['password', 'password_confirmation', 'new_password']));
        }
    }

    public function update(Request $request, int $id)
    {
        $user = AdminUser::findOrFail($id);
        app(\App\Services\AdminAccountProtection::class)->authorize($user);

        $request->merge([
            'contact_number' => preg_replace('/\D+/', '', (string) $request->input('contact_number')),
        ]);

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'min:2', 'max:255', "regex:/^[\p{L}\p{M}\s.'-]+$/u"],
            'middle_initial' => ['nullable', 'string', 'max:3', "regex:/^\p{L}\p{M}*\\.?$/u"],
            'last_name' => ['required', 'string', 'min:2', 'max:255', "regex:/^[\p{L}\p{M}\s.'-]+$/u"],
            'email' => 'required|email|unique:admin_users,email,'.$id,
            'username' => ['required', 'string', 'unique:admin_users,username,'.$id, 'min:3', 'max:255', 'regex:/^[a-zA-Z0-9_.\-]+$/'],
            'contact_number' => ['nullable', 'regex:/^09\d{9}$/'],
            'role_id' => 'required|exists:admin_roles,id',
            'department' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspended',
        ], [
            'first_name.regex' => 'First name can only contain letters, spaces, apostrophes, hyphens, and periods.',
            'middle_initial.regex' => 'Middle initial must be a single letter, with an optional period (e.g. A or A.).',
            'last_name.regex' => 'Last name can only contain letters, spaces, apostrophes, hyphens, and periods.',
            'username.regex' => 'Username can only contain letters, numbers, underscores, dots, and hyphens.',
            'contact_number.regex' => 'Contact number must be in the format 09XXXXXXXXX.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation', 'new_password']))
                ->with('form_type', 'edit_user_'.$id);
        }

        app(\App\Services\AdminAccountProtection::class)->authorize($user, (int) $request->role_id);
        DB::beginTransaction();

        try {
            app(\App\Services\AdminAccountProtection::class)->ensureOwnerRemains($user, (int) $request->role_id, $request->status);

            $user->update([
                'first_name' => $request->first_name,
                'middle_initial' => $request->middle_initial ? mb_strtoupper($request->middle_initial) : null,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'contact_number' => $request->contact_number,
                'role_id' => $request->role_id,
                'department' => $request->department,
                'status' => $request->status,
            ]);

            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'UPDATE',
                'module' => 'Users',
                'details' => json_encode([
                    'user_id' => $user->id,
                    'changes' => $user->getChanges(),
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update user.')
                ->withInput($request->except(['password', 'password_confirmation', 'new_password']));
        }
    }

    public function destroy(Request $request, int $id)
    {
        $user = AdminUser::findOrFail($id);
        app(\App\Services\AdminAccountProtection::class)->authorize($user);

        if ($user->role && $user->role->name === 'super_admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete super admin user.');
        }

        if ($user->id === Auth::guard('admin')->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        DB::beginTransaction();

        try {

            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'DELETE',
                'module' => 'Users',
                'details' => json_encode(['user_id' => $user->id, 'user_name' => $user->first_name.' '.$user->last_name]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $user->delete();

            DB::commit();
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to delete user.');
        }
    }

    public function resetPassword(Request $request, int $id)
    {
        $user = AdminUser::findOrFail($id);
        app(\App\Services\AdminAccountProtection::class)->authorize($user);

        if (! $request->filled('new_password') && $request->filled('password')) {
            $request->merge([
                'new_password' => $request->input('password'),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'new_password' => ['required', 'regex:/^\S+$/', PasswordRules::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'new_password.regex' => 'New password cannot contain spaces.',
            'new_password.*' => 'New password must be at least 8 characters and include uppercase, lowercase, number, and special character.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation', 'new_password']))
                ->with('form_type', 'edit_user_'.$id);
        }

        DB::beginTransaction();

        try {
            $newPassword = (string) $request->new_password;

            $user->update([
                'password' => Hash::make($newPassword),
            ]);

            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'RESET_PASSWORD',
                'module' => 'Users',
                'details' => json_encode(['user_id' => $user->id]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index')
                ->with('success', 'Password reset successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to reset password.');
        }
    }

    public function updatePermissions(Request $request, int $id)
    {
        $user = AdminUser::findOrFail($id);
        app(\App\Services\AdminAccountProtection::class)->authorize($user);

        if ($user->role && $user->role->name === 'super_admin') {
            return redirect()->route('admin.users.index', ['tab' => 'users'])
                ->with('form_type', 'user_permissions_'.$id)
                ->with('error', 'Super admin permissions cannot be modified.');
        }

        $validator = Validator::make($request->all(), [
            'permissions' => 'array',
            'permissions.*' => 'exists:admin_permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation', 'new_password']))
                ->with('form_type', 'user_permissions_'.$id);
        }

        DB::beginTransaction();

        try {
            $selectedPermissionIds = collect($request->input('permissions', []))
                ->filter()
                ->map(fn ($value) => (int) $value)
                ->unique()
                ->values();

            $rolePermissionIds = $user->role
                ? $user->role->permissions()->pluck('admin_permissions.id')
                : collect();

            $directPermissionIds = $selectedPermissionIds->diff($rolePermissionIds)->values()->all();

            $user->permissions()->sync($directPermissionIds);

            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'UPDATE_PERMISSIONS',
                'module' => 'Users',
                'details' => json_encode([
                    'user_id' => $user->id,
                    'selected_permissions' => $selectedPermissionIds,
                    'direct_permissions' => $directPermissionIds,
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            $this->clearUserRoleLookupCache();

            return redirect()->route('admin.users.index', ['tab' => 'users'])
                ->with('success', 'User permissions updated successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('admin.users.index', ['tab' => 'users'])
                ->with('form_type', 'user_permissions_'.$id)
                ->with('error', 'Failed to update permissions.');
        }
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:delete,activate,deactivate,suspend',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:admin_users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if ($request->action !== 'delete' && ! Auth::guard('admin')->user()->hasPermission('update_users')) {
            abort(403, 'You do not have permission to change account status.');
        }
        $protected = AdminUser::whereIn('id', $request->user_ids)->where(function ($query) {
            $query->where('id', Auth::guard('admin')->id())->orWhereHas('role', fn ($role) => $role->where('name', 'super_admin'));
        })->exists();
        if ($protected) {
            return response()->json(['message' => 'Your own account and super-administrator accounts cannot be changed in bulk. Select other accounts.'], 422);
        }

        DB::beginTransaction();

        try {
            $users = AdminUser::whereIn('id', $request->user_ids);

            switch ($request->action) {
                case 'delete':

                    $users->whereDoesntHave('role', function ($q) {
                        $q->where('name', 'super_admin');
                    })->where('id', '!=', Auth::guard('admin')->id())->delete();
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

            AdminActivityLog::create([
                'user_id' => Auth::guard('admin')->id(),
                'action' => 'BULK_'.strtoupper($request->action),
                'module' => 'Users',
                'details' => json_encode(['user_ids' => $request->user_ids, 'action' => $request->action]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            $this->clearUserRoleLookupCache();

            return response()->json(['success' => true, 'message' => 'Bulk action completed successfully.']);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to complete bulk action.'], 500);
        }
    }
}
