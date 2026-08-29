<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function users()
    {
        return $this->hasMany(AdminUser::class, 'role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(
            AdminPermission::class,
            'admin_role_permissions',  
            'role_id',                 
            'permission_id'            
        )->withTimestamps();           
    }

    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }

    public function getDisplayNameAttribute(string $value)
    {
        return $value ?? ucfirst($this->name);
    }

    public function scopeSystem(Builder $query): Builder
    {
        return $query->whereRaw('is_system_role IS TRUE');
    }

    public function scopeCustom(Builder $query): Builder
    {
        return $query->whereRaw('is_system_role IS FALSE');
    }

    public function hasPermission(string $permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    public function getPermissionNames()
    {
        return $this->permissions->pluck('name');
    }
}