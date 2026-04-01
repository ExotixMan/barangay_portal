<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusRemarkHistory extends Model
{
    protected $fillable = [
        'request_type',
        'request_id',
        'reference_number',
        'status',
        'remarks',
        'channel',
        'recipient',
        'admin_user_id',
    ];

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
}
