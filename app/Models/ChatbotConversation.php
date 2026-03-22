<?php
// app/Models/ChatbotConversation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatbotConversation extends Model
{
    public    $timestamps   = false;
    protected $table        = 'chatbot_conversations';
    protected $fillable     = ['session_id', 'started_at', 'ended_at'];
    protected $casts        = ['started_at' => 'datetime', 'ended_at' => 'datetime'];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatbotMessage::class, 'session_id', 'session_id');
    }
}
