<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'expected_completion',
        'location',
        'status',
        'progress'
    ];

    protected $casts = [
        'start_date' => 'date',
        'expected_completion' => 'date',
        'progress' => 'integer'
    ];

    // Calculate days remaining
    public function getDaysRemainingAttribute()
    {
        if (!$this->expected_completion || $this->status === 'completed') {
            return null;
        }
        
        $today = now()->startOfDay();
        $completionDate = $this->expected_completion->startOfDay();
        
        if ($completionDate->lessThan($today)) {
            return 0;
        }
        
        return $today->diffInDays($completionDate);
    }

    // Check if project is overdue
    public function getIsOverdueAttribute()
    {
        if ($this->status === 'completed' || !$this->expected_completion) {
            return false;
        }
        
        return $this->expected_completion->isPast();
    }

    // Get progress color class
    public function getProgressColorAttribute()
    {
        if ($this->progress >= 75) return 'success';
        if ($this->progress >= 50) return 'info';
        if ($this->progress >= 25) return 'warning';
        return 'danger';
    }

}
