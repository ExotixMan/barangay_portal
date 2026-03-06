<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        /** @var AdminUser|null $user */
        $user = Auth::guard('admin')->user();
        
        if (!$user) {
            abort(403, 'Unauthorized - No user');
        }
        
        // Super admin bypass
        if ($user->role && $user->role->name === 'super_admin') {
            return $next($request);
        }
        
        // Check permission
        if (!$user->hasPermission($permission)) {
            abort(403, "Unauthorized - Missing permission: {$permission}");
        }
        
        return $next($request);
    }
}