<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Residents extends Authenticatable
{
    use Notifiable;

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
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
