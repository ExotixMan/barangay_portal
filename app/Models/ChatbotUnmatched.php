<?php
// app/Models/ChatbotUnmatched.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotUnmatched extends Model
{
    public    $timestamps   = false;
    protected $table        = 'chatbot_unmatched';
    protected $fillable     = ['query', 'session_id', 'resolved', 'created_at'];
    protected $casts        = ['resolved' => 'boolean', 'created_at' => 'datetime'];
}
