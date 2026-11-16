<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentProfileCorrectionConcern extends Model
{
    private bool $resolutionSnapshotLoaded = false;

    private ?string $resolutionSnapshot = null;

    protected $fillable = [
        'resident_id',
        'service',
        'field_name',
        'current_value',
        'correct_value',
        'resolved_value',
        'message',
        'status',
        'reviewed_by',
        'reviewed_at',
        'remarks',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function getResolvedValueAttribute($value): ?string
    {
        if ($value !== null) {
            return (string) $value;
        }
        if (! $this->exists || $this->status !== 'resolved') {
            return null;
        }
        // Older deployments may not have the optional snapshot column yet.
        // The same immutable value is recorded in the transaction's audit log.
        if (! $this->resolutionSnapshotLoaded) {
            $this->resolutionSnapshotLoaded = true;
            $log = AdminActivityLog::where('module', 'Residents')->where('action', 'UPDATE')
                ->where('details', 'like', '%"correction_id":'.(int) $this->id.',%')
                ->where('details', 'like', '%"requested_value":%')->latest('id')->first();
            $details = $log?->details;
            if (is_array($details) && (int) ($details['correction_id'] ?? 0) === (int) $this->id
                && (int) ($details['resident_id'] ?? 0) === (int) $this->resident_id
                && ($details['decision'] ?? null) === 'resolved' && is_string($details['new_value'] ?? null)) {
                $this->resolutionSnapshot = $details['new_value'];
            }
        }

        return $this->resolutionSnapshot;
    }

    public function resident()
    {
        return $this->belongsTo(Residents::class, 'resident_id', 'id')
            ->withTrashed();
    }

    public function getFormattedIdAttribute(): string
    {
        $year = optional($this->created_at)->format('Y') ?: now()->format('Y');

        return 'PCC-'.$year.'-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }
}
