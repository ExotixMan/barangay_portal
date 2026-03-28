<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, ...$permissions)
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

        $requiredPermissions = collect($permissions)
            ->flatMap(function ($permission) {
                return explode('|', (string) $permission);
            })
            ->map(function ($permission) {
                return trim($permission);
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($requiredPermissions)) {
            return $next($request);
        }
        
        // Allow access when user has at least one required permission.
        if (!$user->hasAnyPermission($requiredPermissions)) {
            abort(403, 'Unauthorized - Missing one of permissions: ' . implode(', ', $requiredPermissions));
        }
        
        return $next($request);
    }
}