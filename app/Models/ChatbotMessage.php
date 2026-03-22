<?php
// app/Models/ChatbotMessage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotMessage extends Model
{
    public    $timestamps   = false;
    protected $table        = 'chatbot_messages';
    protected $fillable     = ['session_id', 'role', 'content', 'matched', 'knowledge_id', 'created_at'];
    protected $casts        = ['matched' => 'boolean', 'created_at' => 'datetime'];

    public function knowledge(): BelongsTo
    {
        return $this->belongsTo(ChatbotKnowledge::class, 'knowledge_id');
    }
}
