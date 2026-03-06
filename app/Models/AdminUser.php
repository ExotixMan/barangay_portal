<?php
// app/Models/AdminUser.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasPermissions; // This trait now contains ALL permission methods

class AdminUser extends Authenticatable
{
    use HasApiTokens, 
        HasFactory, 
        Notifiable, 
        SoftDeletes, 
        HasPermissions; // This single trait provides all permission methods

    protected $table = 'admin_users'; // Explicitly set table name

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'contact_number',
        'role_id',
        'department',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
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
        $lastUser = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastUser && $lastUser->user_id) {
            $lastNumber = intval(substr($lastUser->user_id, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . '-' . $year . '-' . $newNumber;
    }

    // Relationships
    public function role()
    {
        return $this->belongsTo(AdminRole::class, 'role_id');
    }

    public function activities()
    {
        return $this->hasMany(AdminActivityLog::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getInitialsAttribute()
    {
        $first = $this->first_name ? substr($this->first_name, 0, 1) : '';
        $last = $this->last_name ? substr($this->last_name, 0, 1) : '';
        return strtoupper($first . $last);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%")
              ->orWhere('user_id', 'like', "%{$search}%");
        });
    }
}