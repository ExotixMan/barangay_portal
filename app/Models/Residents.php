<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerifyEmail;

class Residents extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, MustVerifyEmailTrait, SoftDeletes; 

    protected $table = 'residents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'email',
        'address',
        'birthdate',
        'contact',
        'username',
        'password',
        'valid_id',
        'valid_id_verified',
        'profile_photo',
        'phone_otp',
        'phone_otp_expires_at',
        'phone_verified',
        'email_verified_at',
    ];

    protected $casts = [
        'phone_verified' => 'boolean',
        'valid_id_verified' => 'boolean',
        'phone_otp_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        $parts = array_filter([
            $this->firstname,
            $this->middlename,
            $this->lastname,
            $this->suffix,
        ]);
        return implode(' ', $parts);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
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
