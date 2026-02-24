<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportFile extends Model
{
    protected $fillable = [
        'blotter_report_id',
        'file_path',
        'file_type'
    ];
}
