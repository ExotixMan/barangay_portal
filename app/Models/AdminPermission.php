<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'module',
        'description'
    ];

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(
            AdminRole::class,
            'admin_role_permissions',  // pivot table name
            'permission_id',            // foreign key for this model in pivot table
            'role_id'                   // foreign key for the related model in pivot table
        )->withTimestamps();            // since pivot table has created_at/updated_at
    }

    // Scopes
    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public static function getModules()
    {
        return self::select('module')->distinct()->pluck('module');
    }
}