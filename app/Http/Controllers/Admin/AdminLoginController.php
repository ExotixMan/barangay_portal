<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\AdminActivityLog;
use App\Models\AdminUser;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return $this->redirectBasedOnRole(Auth::guard('admin')->user());
        }
        
        return view('barangay_system.login');
    }

    public function login(Request $request)
    {
        $request->merge([
            'login' => trim((string) $request->input('login', $request->input('email', ''))),
        ]);

        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loginInput = (string) $request->input('login');
        $password = (string) $request->input('password');

        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginField => $loginInput,
            'password' => $password,
        ];

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('admin')->user();

            if ($user->status !== 'active') {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'login' => 'Your account is not active. Please contact administrator.',
                ])->withInput($request->except('password'));
            }

            DB::table('admin_users')->where('id', $user->id)->update(['last_login_at' => now()]);

            $this->storeLoginTime($request);

            $this->logActivity($user, 'LOGIN', 'Authentication', [
                'email' => $user->email,
                'username' => $user->username,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    protected function redirectBasedOnRole(AdminUser $user)
    {
        
        if (method_exists($user, 'load')) {
            $user->load('role');
        }

        $roleName = $user->role ? $user->role->name : null;

        $permissionRoutes = [
            'view_forecast' => 'admin.dashboard.index',
            'view_dashboard' => 'admin.dashboard.index',
            'view_residents' => 'admin.residents.index',
            'view_residency' => 'admin.residency.index',
            'view_indigency' => 'admin.indigency.index',
            'view_clearance' => 'admin.clearance.index',
            'view_blotter' => 'admin.blotter.index',
            'view_announcements' => 'admin.announcements.index',
            'view_events' => 'admin.events.index',
            'view_projects' => 'admin.projects.index',
            'view_requests' => 'admin.request.index',
            'view_users' => 'admin.users.index',
            'view_roles' => 'admin.roles.index',
            'view_permissions' => 'admin.permissions.index',
        ];

        foreach ($permissionRoutes as $permission => $route) {
            if ($this->checkPermission($user, $permission)) {
                
                $this->logActivity($user, 'REDIRECT', 'Authorization', [
                    'method' => 'permission_based',
                    'permission' => $permission,
                    'redirect_to' => $route
                ]);
                
                return redirect()->intended(route($route));
            }
        }

        switch ($roleName) {
            case 'super_admin':
                
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'barangay_captain':
                
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'barangay_secretary':
                
                if ($this->checkPermission($user, 'view_clearance')) {
                    return redirect()->intended(route('admin.clearance.index'));
                }
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'barangay_treasurer':
                
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'barangay_kagawad':
                
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'records_officer':
                
                if ($this->checkPermission($user, 'view_residents')) {
                    return redirect()->intended(route('admin.residents.index'));
                }
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'certificate_encoder':
                
                if ($this->checkPermission($user, 'view_requests')) {
                    return redirect()->intended(route('admin.request.index'));
                }
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'complaints_officer':
                
                if ($this->checkPermission($user, 'view_blotter')) {
                    return redirect()->intended(route('admin.blotter.index'));
                }
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'staff':
                
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'viewer':
                
                return redirect()->intended(route('admin.dashboard.index'));
                
            default:
                
                return redirect()->intended(route('admin.dashboard.index'));
        }
    }

    protected function checkPermission(AdminUser $user, string $permissionName)
    {
        
        if ($user->role && $user->role->name === 'super_admin') {
            return true;
        }

        if ($user->role && method_exists($user->role, 'permissions')) {
            return $user->role->permissions()
                ->where('name', $permissionName)
                ->exists();
        }
        
        return false;
    }

    protected function logActivity(AdminUser $user, string $action, string $module, array $details = [])
    {
        try {
            AdminActivityLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'module' => $module,
                'details' => array_merge($details, [
                    'timestamp' => now()->toDateTimeString(),
                    'user_name' => $user->first_name . ' ' . $user->last_name,
                    'user_role' => $user->role ? $user->role->display_name : 'No Role'
                ]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        } catch (\Exception $e) {
            
            Log::error('Failed to log admin activity: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('admin')->user();

        if ($user) {
            $this->logActivity($user, 'LOGOUT', 'Authentication', [
                'email' => $user->email,
                'username' => $user->username,
                'session_duration' => $this->getSessionDuration($request)
            ]);
        }
        
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    protected function storeLoginTime(Request $request)
    {
        $request->session()->put('login_time', now());
    }

    protected function getSessionDuration(Request $request)
    {
        $loginTime = $request->session()->get('login_time');
        if ($loginTime) {
            return now()->diffInMinutes($loginTime) . ' minutes';
        }
        return null;
    }
}