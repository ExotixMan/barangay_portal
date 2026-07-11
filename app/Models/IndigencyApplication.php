<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndigencyApplication extends Model
{
    use SoftDeletes;

    protected $table = 'indigency_applications';

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
        'monthly_income',
        'household_members',
        'purpose',
        'purpose_other',
        'primary_proof',
        'valid_id_path',
        'status',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'household_members' => 'integer',
    ];

    public function getFullNameAttribute(): string
    {
        return collect([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ])
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => trim((string) $value))
            ->implode(' ');
    }

    public function getMonthlyIncomeLabelAttribute(): string
    {
        return match (strtolower(trim((string) $this->monthly_income))) {
            'below 5k' => 'Below ₱5,000',
            '5k-8k' => '₱5,000–₱8,000',
            '8k-10k' => '₱8,001–₱10,000',
            '10k-15k' => '₱10,001–₱15,000',
            'no income' => 'No Fixed Income',
            default => filled($this->monthly_income)
                ? ucwords(str_replace(['_', '-'], [' ', '–'], (string) $this->monthly_income))
                : 'Not Provided',
        };
    }

    public function getPurposeLabelAttribute(): string
    {
        return match (strtolower(trim((string) $this->purpose))) {
            'medical' => 'Medical / Hospital Assistance',
            'scholarship' => 'Scholarship / Financial Aid',
            'government' => 'Government Program',
            'legal' => 'Legal Assistance',
            'employment' => 'Employment Requirement',
            'burial' => 'Burial Assistance',
            'other' => 'Other Purpose',
            default => filled($this->purpose)
                ? ucwords(str_replace('_', ' ', (string) $this->purpose))
                : 'Not Provided',
        };
    }

    public function getHouseholdLabelAttribute(): string
    {
        $count = (int) $this->household_members;

        if ($count <= 0) {
            return 'Household size not provided';
        }

        return $count . ' household ' . ($count === 1 ? 'member' : 'members');
    }
}
