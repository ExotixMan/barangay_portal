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
        
        return view('admin.admin_login');
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

        // Support either email or username in one field.
        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginField => $loginInput,
            'password' => $password,
        ];
        
        // Use the admin guard
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Get the authenticated admin user
            $user = Auth::guard('admin')->user();
            
            // Check if user is active
            if ($user->status !== 'active') {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'login' => 'Your account is not active. Please contact administrator.',
                ])->withInput($request->except('password'));
            }
            
            // Update the last_login_at timestamp for accurate login tracking
            DB::table('admin_users')->where('id', $user->id)->update(['last_login_at' => now()]);
            
            // Store login time in session
            $this->storeLoginTime($request);
            
            // Log the login activity
            $this->logActivity($user, 'LOGIN', 'Authentication', [
                'email' => $user->email,
                'username' => $user->username,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Redirect based on user's role and permissions
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    //Redirect user based on their role and permissions
    protected function redirectBasedOnRole($user)
    {
        // Load the user's role if relationship exists
        if (method_exists($user, 'load')) {
            $user->load('role');
        }
        
        // Get the role name
        $roleName = $user->role ? $user->role->name : null;
        
        // First, check if user has any specific module permissions
        $permissionRoutes = [
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
        
        // Check each permission and redirect to the first available
        foreach ($permissionRoutes as $permission => $route) {
            if ($this->checkPermission($user, $permission)) {
                // Log the permission-based redirection
                $this->logActivity($user, 'REDIRECT', 'Authorization', [
                    'method' => 'permission_based',
                    'permission' => $permission,
                    'redirect_to' => $route
                ]);
                
                return redirect()->intended(route($route));
            }
        }
        
        // If no specific permissions, try role-based redirection
        switch ($roleName) {
            case 'super_admin':
                // Super admin has full access - go to main dashboard
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'barangay_captain':
                // Captain can see overall dashboard
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'barangay_secretary':
                // Secretary focuses on documents and records
                if ($this->checkPermission($user, 'view_clearance')) {
                    return redirect()->intended(route('admin.clearance.index'));
                }
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'barangay_treasurer':
                // Treasurer focuses on financial records
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'barangay_kagawad':
                // Kagawad focuses on their assigned committees
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'records_officer':
                // Records officer focuses on resident records
                if ($this->checkPermission($user, 'view_residents')) {
                    return redirect()->intended(route('admin.residents.index'));
                }
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'certificate_encoder':
                // Certificate encoder focuses on certificate requests
                if ($this->checkPermission($user, 'view_requests')) {
                    return redirect()->intended(route('admin.request.index'));
                }
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'complaints_officer':
                // Complaints officer focuses on blotter/complaints
                if ($this->checkPermission($user, 'view_blotter')) {
                    return redirect()->intended(route('admin.blotter.index'));
                }
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'staff':
                // General staff - show dashboard
                return redirect()->intended(route('admin.dashboard.index'));
                
            case 'viewer':
                // Viewer - can only view dashboard
                return redirect()->intended(route('admin.dashboard.index'));
                
            default:
                // Default fallback to dashboard
                return redirect()->intended(route('admin.dashboard.index'));
        }
    }

    /**
     * Check if user has a specific permission using your permission system
     */
    protected function checkPermission($user, $permissionName)
    {
        // If user is super admin, they have all permissions
        if ($user->role && $user->role->name === 'super_admin') {
            return true;
        }
        
        // Check if the user's role has the permission
        if ($user->role && method_exists($user->role, 'permissions')) {
            return $user->role->permissions()
                ->where('name', $permissionName)
                ->exists();
        }
        
        return false;
    }

    /**
     * Log user activity using AdminActivityLog model
     */
    protected function logActivity($user, $action, $module, $details = [])
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
            // Log error but don't interrupt the login process
            Log::error('Failed to log admin activity: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('admin')->user();
        
        // Log the logout activity
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
        
        return redirect()->route('admin.login');
    }

    /**
     * Store login time in session
     */
    protected function storeLoginTime($request)
    {
        $request->session()->put('login_time', now());
    }

    /**
     * Calculate session duration
     */
    protected function getSessionDuration($request)
    {
        $loginTime = $request->session()->get('login_time');
        if ($loginTime) {
            return now()->diffInMinutes($loginTime) . ' minutes';
        }
        return null;
    }
}