<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'type',
        'attendees',
        'image'
    ];

    protected $casts = [
        'event_date' => 'date',
        'attendees' => 'integer'
    ];

    protected $dates = ['event_date'];
}
