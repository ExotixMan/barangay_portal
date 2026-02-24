<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlotterReport extends Model
{
    protected $fillable = [
        'reference_number',
        'report_type',
        'incident_date',
        'incident_time',
        'location',
        'description',
        'immediate_action',
        'complainant_name',
        'complainant_contact',
        'complainant_address',
        'complainant_email',
        'respondent_name',
        'respondent_contact',
        'respondent_address',
        'respondent_description',
        'confidentiality',
        'additional_info'
    ];

    public function witnesses()
    {
        return $this->hasMany(Witness::class);
    }

    public function files()
    {
        return $this->hasMany(ReportFile::class);
    }
}
