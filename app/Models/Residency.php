<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Residency extends Model
{
    protected $table = 'residency_applications'; // if your table is named residencies

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
        'status_updated_at',
    ];
}
