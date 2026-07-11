<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class BlotterReport extends Model
{
    use SoftDeletes;

    protected $table = 'blotter_reports';

    protected $fillable = [
        'user_id',
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
        'additional_info',
        'status',
    ];

    protected $casts = [
        'incident_date' => 'date',
    ];

    public function witnesses(): HasMany
    {
        return $this->hasMany(Witness::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ReportFile::class);
    }

    public function getReportTypeLabelAttribute(): string
    {
        return match (strtolower((string) $this->report_type)) {
            'dispute' => 'Community Dispute',
            'security' => 'Security Concern',
            'public' => 'Public Safety',
            'other' => 'Other Concern',
            default => 'Unclassified',
        };
    }

    public function getConfidentialityLabelAttribute(): string
    {
        return match (strtolower((string) $this->confidentiality)) {
            'low', 'public' => 'Public Report',
            'medium', 'confidential' => 'Confidential Report',
            'high', 'anonymous' => 'Anonymous Report',
            default => 'Not specified',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->trashed()) {
            return 'Archived';
        }

        return match (strtolower((string) $this->status)) {
            'resolved' => 'Resolved',
            'dropped' => 'Dropped',
            'processing' => 'Processing',
            default => 'Processing',
        };
    }

    public function getIncidentDateLabelAttribute(): string
    {
        return $this->incident_date?->format('M d, Y') ?? 'Date not set';
    }

    public function getIncidentTimeLabelAttribute(): string
    {
        if (blank($this->incident_time)) {
            return 'Time not set';
        }

        try {
            return Carbon::parse((string) $this->incident_time)->format('h:i A');
        } catch (\Throwable) {
            return (string) $this->incident_time;
        }
    }

    public function getIncidentScheduleLabelAttribute(): string
    {
        $date = $this->incident_date_label;

        if (blank($this->incident_time)) {
            return $date;
        }

        return $date . ' · ' . $this->incident_time_label;
    }
}
