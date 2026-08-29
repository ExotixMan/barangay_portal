<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatbotKnowledge extends Model
{
    protected $table = 'chatbot_knowledge';

    protected $fillable = [
        'question',
        'answer',
        'keywords',
        'category',
        'usage_count',
    ];

    protected $casts = [
        'usage_count' => 'integer',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatbotMessage::class, 'knowledge_id');
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    public static function search(string $query, int $limit = 10): EloquentCollection
    {
        $query = mb_strtolower(trim($query));

        if ($query === '') {
            return new EloquentCollection();
        }

        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $query) . '%';

        return static::query()
            ->where(function ($q) use ($like) {
                $q->whereRaw("LOWER(COALESCE(question, '')) LIKE ?", [$like])
                    ->orWhereRaw("LOWER(COALESCE(answer, '')) LIKE ?", [$like])
                    ->orWhereRaw("LOWER(COALESCE(keywords, '')) LIKE ?", [$like])
                    ->orWhereRaw("LOWER(COALESCE(category, '')) LIKE ?", [$like]);
            })
            ->orderByDesc('usage_count')
            ->orderBy('question')
            ->limit(max(1, min($limit, 25)))
            ->get();
    }
}
