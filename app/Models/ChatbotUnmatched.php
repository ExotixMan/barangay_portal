<?php

namespace App\Models;

use App\Casts\DatabaseBoolean;
use Illuminate\Database\Eloquent\Model;

class ChatbotUnmatched extends Model
{
    public $timestamps = false;

    protected $table = 'chatbot_unmatched';

    protected $fillable = [
        'query',
        'session_id',
        'resolved',
        'created_at',
    ];

    protected $casts = [
        'resolved' => DatabaseBoolean::class,
        'created_at' => 'datetime',
    ];
}
