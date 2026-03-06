<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_system_role'
    ];

    protected $casts = [
        'is_system_role' => 'boolean',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(AdminUser::class, 'role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(
            AdminPermission::class,
            'admin_role_permissions',  // pivot table name
            'role_id',                 // foreign key for this model in pivot table
            'permission_id'            // foreign key for the related model in pivot table
        )->withTimestamps();           // since pivot table has created_at/updated_at
    }

    // Accessors
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }

    public function getDisplayNameAttribute($value)
    {
        return $value ?? ucfirst($this->name);
    }

    // Scopes
    public function scopeSystem($query)
    {
        return $query->where('is_system_role', true);
    }

    public function scopeCustom($query)
    {
        return $query->where('is_system_role', false);
    }

    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    public function getPermissionNames()
    {
        return $this->permissions->pluck('name');
    }
}