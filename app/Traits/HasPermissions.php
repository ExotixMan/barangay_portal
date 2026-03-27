<?php
// app/Traits/HasPermissions.php

namespace App\Traits;

trait HasPermissions
{
    // Remove these methods from the trait since they're in the model
    // Or keep them and remove from model - your choice
    
    // If you want to keep using the trait, make sure it has ALL methods:
    
    public function hasPermission($permission)
    {
        if (!$this->role && !$this->permissions()->exists()) {
            return false;
        }
        
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        $hasRolePermission = $this->role
            ? $this->role->permissions()->where('name', $permission)->exists()
            : false;

        $hasDirectPermission = $this->permissions()->where('name', $permission)->exists();

        return $hasRolePermission || $hasDirectPermission;
    }

    public function hasAnyPermission(array $permissions)
    {
        if (!$this->role && !$this->permissions()->exists()) {
            return false;
        }
        
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        $hasRolePermission = $this->role
            ? $this->role->permissions()->whereIn('name', $permissions)->exists()
            : false;

        $hasDirectPermission = $this->permissions()->whereIn('name', $permissions)->exists();

        return $hasRolePermission || $hasDirectPermission;
    }

    public function hasAllPermissions($permissions)
    {
        if (!$this->role) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    public function hasModuleAccess($module)
    {
        if (!$this->role) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->role->permissions()
            ->where('module', $module)
            ->count();
    }

    public function getAllPermissions()
    {
        if (!$this->role && !$this->permissions()->exists()) {
            return collect();
        }

        $rolePermissions = $this->role ? $this->role->permissions : collect();
        $directPermissions = $this->permissions;

        return $rolePermissions->merge($directPermissions)->unique('id')->values();
    }

    public function getPermissionNames()
    {
        if (!$this->role && !$this->permissions()->exists()) {
            return collect();
        }

        return $this->getAllPermissions()->pluck('name');
    }

    public function isSuperAdmin()
    {
        return $this->role && $this->role->name === 'super_admin';
    }

    // Role check methods
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