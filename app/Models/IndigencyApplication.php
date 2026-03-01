<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndigencyApplication extends Model
{

    protected $table = 'indigency_applications';

    protected $fillable = [
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
        'valid_id_path',
        'status',
    ];
}
