<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AdminPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'module',
        'description'
    ];

    public function roles()
    {
        return $this->belongsToMany(
            AdminRole::class,
            'admin_role_permissions',  
            'permission_id',            
            'role_id'                   
        )->withTimestamps();            
    }

    public function scopeByModule(Builder $query, string $module): Builder
    {
        return $query->where('module', $module);
    }

    public static function getModules()
    {
        return self::select('module')->distinct()->pluck('module');
    }
}