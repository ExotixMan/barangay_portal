<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Witness extends Model
{
    protected $fillable = [
        'blotter_report_id',
        'name',
        'contact',
        'statement'
    ];
}
