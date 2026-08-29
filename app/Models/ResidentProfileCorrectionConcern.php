<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentProfileCorrectionConcern extends Model
{
    protected $fillable = [
        'resident_id',
        'service',
        'field_name',
        'current_value',
        'correct_value',
        'message',
        'status',
        'reviewed_by',
        'reviewed_at',
        'remarks',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function resident()
    {
        return $this->belongsTo(Residents::class, 'resident_id', 'id')
            ->withTrashed();
    }

    public function getFormattedIdAttribute(): string
    {
        $year = optional($this->created_at)->format('Y') ?: now()->format('Y');

        return 'PCC-' . $year . '-' . str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }
}
