<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangayClearance extends Model
{
    use SoftDeletes;

    protected $table = 'barangay_clearances';

    protected $fillable = [
        'user_id',
        'reference_number',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthdate',
        'gender',
        'address',
        'contact_number',
        'email',
        'primary_proof',
        'valid_id_path',
        'purpose',
        'purpose_other',
        'fee',
        'status',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'fee' => 'decimal:2',
    ];

    public function getFullNameAttribute(): string
    {
        return collect([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ])->filter()->implode(' ');
    }

    public function getPurposeLabelAttribute(): string
    {
        return match ($this->purpose) {
            'employment' => 'Employment',
            'business' => 'Business Permit',
            'scholarship' => 'Scholarship',
            'travel' => 'Travel / Abroad',
            'bank' => 'Bank Transaction',
            'government' => 'Government Transaction',
            'school' => 'School Requirement',
            'other' => $this->purpose_other ?: 'Other Purpose',
            default => 'N/A',
        };
    }

    public function getFeeLabelAttribute(): string
    {
        return $this->fee !== null
            ? '₱' . number_format((float) $this->fee, 2)
            : 'To be determined';
    }
}