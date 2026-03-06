<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

abstract class BaseAdminController extends Controller
{
    /**
     * Get the authenticated admin user
     */
    protected function getUser()
    {
        return Auth::user();
    }

    /**
     * Check if user has permission
     */
    protected function hasPermission($permission)
    {
        /** @var AdminUser|null $user */
        $user = $this->getUser();
        return $user && $user->hasPermission($permission);
    }

    /**
     * Authorize or abort
     */
    protected function authorizePermission($permission)
    {
        if (!$this->hasPermission($permission)) {
            abort(403, 'Unauthorized action. You need permission: ' . $permission);
        }
    }

    /**
     * Check if user has any of the given permissions
     */
    protected function hasAnyPermission($permissions)
    {
        /** @var AdminUser|null $user */
        $user = $this->getUser();
        return $user && $user->hasAnyPermission($permissions);
    }

    /**
     * Get user role
     */
    protected function getUserRole()
    {
        /** @var AdminUser|null $user */
        $user = $this->getUser();
        return $user ? $user->getRoleName() : null;
    }

    /**
     * Check if user has role
     */
    protected function hasRole($role)
    {
        $user = $this->getUser();
        return $user && $user->role && $user->role->name === $role;
    }
}