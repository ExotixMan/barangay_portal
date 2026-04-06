<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndigencyApplication extends Model
{
    use SoftDeletes;


    protected $table = 'indigency_applications';

    protected $fillable = [
        'user_id',
        'reference_number',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthdate',
        'gender',
        'address',
        'contact_number',
        'email',
        'monthly_income',
        'household_members',
        'purpose',
        'purpose_other',
        'primary_proof',
        'valid_id_path',
        'status',
    ];
}
