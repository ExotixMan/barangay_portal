<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatbotKnowledge extends Model
{
    protected $table    = 'chatbot_knowledge';
    protected $fillable = ['question', 'answer', 'keywords', 'category', 'usage_count'];
    protected $casts    = ['usage_count' => 'integer'];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatbotMessage::class, 'knowledge_id');
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Search knowledge base — ilike on question, answer, AND keywords.
     * Accepts a full phrase OR a single keyword.
     */
    public static function search(string $query, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        $query = strtolower(trim($query));
        if (empty($query)) return collect();

        $like = '%' . $query . '%';

        return static::where(function ($q) use ($like) {
                $q->whereRaw('LOWER(question) LIKE ?', [$like])
                  ->orWhereRaw('LOWER(answer) LIKE ?', [$like])
                  ->orWhereRaw('LOWER(keywords) LIKE ?', [$like])
                  ->orWhereRaw('LOWER(category) LIKE ?', [$like]);
            })
            ->orderByDesc('usage_count')
            ->limit($limit)
            ->get();
    }
}