<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangayClearance extends Model
{
    use SoftDeletes;


    protected $table = 'barangay_clearances';
    
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
        'primary_proof',
        'valid_id_path',
        'purpose',
        'purpose_other',
        'fee',
        'status'
    ];

    protected $casts = [
        'fee' => 'decimal:2',
    ];
}
