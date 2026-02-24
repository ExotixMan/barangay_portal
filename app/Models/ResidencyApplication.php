<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidencyApplication extends Model
{
    protected $fillable = [
        'reference_number',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthdate',
        'gender',
        'civil_status',
        'birth_place',
        'address',
        'years_residing',
        'residency_type',
        'contact_number',
        'email',
        'household_members',
        'purpose',
        'purpose_other',
        'primary_proof',
        'government_id',
        'status',
    ];
}
