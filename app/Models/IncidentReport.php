<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{

    public $timestamps = false;
    
    protected $primaryKey = 'incident_id';


    protected $fillable = [
        'resident_id',
        "full_name",
        "address",
        "location",
        "date_of_incident",
        "contact_number",
        "type_of_incident",
        "description",
        "proof_of_incident",
        "status"
    ];
}
