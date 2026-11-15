<?php

namespace App\Traits;

trait HasPermissions
{
    // Relations live on this request's model instance. Reuse eager-loaded
    // collections instead of issuing one EXISTS query for every UI checkbox.
    public function hasPermission($permission)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return ($this->role && $this->role->permissions->contains('name', $permission))
            || $this->permissions->contains('name', $permission);
    }

    public function hasAnyPermission(array $permissions)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->getAllPermissions()->whereIn('name', $permissions)->isNotEmpty();
    }

    public function hasAllPermissions($permissions)
    {
        if (! $this->role) {
            return false;
        }
        if ($this->isSuperAdmin()) {
            return true;
        }
        foreach ($permissions as $permission) {
            if (! $this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    public function hasModuleAccess($module)
    {
        if (! $this->role) {
            return false;
        }
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->role->permissions->where('module', $module)->count();
    }

    public function getAllPermissions()
    {
        $rolePermissions = $this->role ? $this->role->permissions : collect();

        return $rolePermissions->merge($this->permissions)->unique('id')->values();
    }

    public function getPermissionNames()
    {
        return $this->getAllPermissions()->pluck('name');
    }

    public function isSuperAdmin()
    {
        return $this->role && $this->role->name === 'super_admin';
    }

    public function isBarangayCaptain()
    {
        return $this->role && $this->role->name === 'barangay_captain';
    }

    public function isBarangaySecretary()
    {
        return $this->role && $this->role->name === 'barangay_secretary';
    }

    public function isBarangayTreasurer()
    {
        return $this->role && $this->role->name === 'barangay_treasurer';
    }

    public function isBarangayKagawad()
    {
        return $this->role && $this->role->name === 'barangay_kagawad';
    }

    public function isStaff()
    {
        return $this->role && $this->role->name === 'staff';
    }

    public function isRecordsOfficer()
    {
        return $this->role && $this->role->name === 'records_officer';
    }

    public function isCertificateEncoder()
    {
        return $this->role && $this->role->name === 'certificate_encoder';
    }

    public function isComplaintsOfficer()
    {
        return $this->role && $this->role->name === 'complaints_officer';
    }

    public function isViewer()
    {
        return $this->role && $this->role->name === 'viewer';
    }

    public function getRoleDisplayName()
    {
        return $this->role->display_name ?? 'Administrator';
    }

    public function getRoleName()
    {
        return $this->role ? $this->role->name : null;
    }
}
