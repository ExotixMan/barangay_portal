<?php
// app/Models/UserActivityLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $table = 'admin_activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'details',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'details' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(AdminUser::class, 'user_id');
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    // Accessors
    public function getFormattedDetailsAttribute()
    {
        if (is_array($this->details)) {
            return json_encode($this->details, JSON_PRETTY_PRINT);
        }
        return $this->details;
    }

    public function getActionBadgeClassAttribute()
    {
        return match($this->action) {
            'LOGIN', 'LOGOUT' => 'bg-info',
            'CREATE' => 'bg-success',
            'UPDATE' => 'bg-warning',
            'DELETE' => 'bg-danger',
            'REDIRECT' => 'bg-secondary',
            default => 'bg-primary'
        };
    }
}