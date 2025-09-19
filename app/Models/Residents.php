<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Residents extends Model
{
    protected $fillable = [
        'firstname', 
        'lastname', 
        'email', 
        'address', 
        'birthdate',
        'contact', 
        'username', 
        'password', 
        'proof_file'
    ];
}
