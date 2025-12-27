<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'resident_id',
        "request_type",
        "full_name",
        "complete_address",
        "age",
        "date_of_birth",
        "contact_number",
        "email_address",
        "purpose_of_request",
        "specify_others",
        "valid_id_path",
        "proof_of_residency_path",
        "remarks"
    ];

}
