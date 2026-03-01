<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Residents extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, MustVerifyEmailTrait, SoftDeletes; 

    protected $table = 'residents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'firstname', 
        'lastname', 
        'email', 
        'address', 
        'birthdate',
        'contact', 
        'username', 
        'password',
        'phone_otp',
        'phone_otp_expires_at',
        'phone_verified',
        'email_verified_at'
    ];

    protected $casts = [
        'phone_verified' => 'boolean',
        'phone_otp_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getAgeAttribute()
    {
        if (!$this->birthdate) {
            return null;
        }

        return Carbon::parse($this->birthdate)->age;
    }

    public function activities()
    {
        return $this->hasMany(ResidentActivity::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
