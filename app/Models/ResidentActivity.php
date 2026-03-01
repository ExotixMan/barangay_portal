<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'residents_id',
        'action',
        'description'
    ];

    // If you have timestamps
    public $timestamps = true;

    // Relationship with Resident
    public function resident()
    {
        return $this->belongsTo(Residents::class, 'residents_id');
    }
}
