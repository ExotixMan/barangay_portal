<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\SupabaseStorageService;

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
    public function getTypeAttribute($value): string
    {
        if (!$this->event_date) {
            return $value ?: 'upcoming';
        }

        return $this->event_date->copy()->startOfDay()->gte(today()) ? 'upcoming' : 'past';
    }

    public function getImageUrlAttribute(): ?string
    {
        return app(SupabaseStorageService::class)->publicUrl($this->image);
    }

}
