<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Residents extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, MustVerifyEmailTrait;

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

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
