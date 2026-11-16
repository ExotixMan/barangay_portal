<?php

namespace App\Models;

use App\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AdminUser extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        HasPermissions,
        Notifiable,
        SoftDeletes;

    protected $table = 'admin_users';

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_initial',
        'last_name',
        'email',
        'username',
        'password',
        'contact_number',
        'role_id',
        'department',
        'status',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setUsernameAttribute($value): void
    {
        $this->attributes['username'] = mb_strtolower(trim((string) $value));
    }

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = mb_strtolower(trim((string) $value));
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->user_id = static::generateUserId();
        });
    }

    protected static function generateUserId()
    {
        $prefix = 'USR';
        $year = date('Y');
        $lastUser = static::withTrashed()->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastUser && $lastUser->user_id) {
            $lastNumber = intval(substr($lastUser->user_id, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix.'-'.$year.'-'.$newNumber;
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'admins.'.$this->getKey();
    }

    public function role()
    {
        return $this->belongsTo(AdminRole::class, 'role_id');
    }

    public function activities()
    {
        return $this->hasMany(AdminActivityLog::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(
            AdminPermission::class,
            'admin_user_permissions',
            'user_id',
            'permission_id'
        )->withTimestamps();
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name.' '.($this->middle_initial ? $this->middle_initial.' ' : '').$this->last_name);
    }

    public function getInitialsAttribute()
    {
        $first = $this->first_name ? substr($this->first_name, 0, 1) : '';
        $last = $this->last_name ? substr($this->last_name, 0, 1) : '';

        return strtoupper($first.$last);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole(Builder $query, int $roleId): Builder
    {
        return $query->where('role_id', $roleId);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('middle_initial', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('user_id', 'like', "%{$search}%");
        });
    }
}
