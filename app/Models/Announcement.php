<?php

namespace App\Models;

use App\Casts\DatabaseBoolean;
use App\Services\SupabaseStorageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category',
        'image',
        'is_featured',
        'views',
        'published_at',
        'status',
    ];

    protected $casts = [
        'is_featured' => DatabaseBoolean::class,
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($announcement) {
            $announcement->slug = static::makeUniqueSlug($announcement->title);
        });

        static::updating(function ($announcement) {
            if ($announcement->isDirty('title')) {
                $announcement->slug = static::makeUniqueSlug($announcement->title, $announcement->id);
            }
        });
    }

    private static function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $base = $base !== '' ? $base : 'announcement';
        $slug = $base;
        $counter = 2;

        while (static::withTrashed()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function getImageUrlAttribute(): string
    {
        $image = trim((string) $this->image);

        if ($image === '') {
            return asset('Images/logo.png');
        }

        $normalized = str_replace('\\', '/', $image);

        if (Str::startsWith($normalized, 'public/')) {
            $normalized = Str::after($normalized, 'public/');
        }

        return app(SupabaseStorageService::class)->publicUrl($normalized) ?? asset('Images/logo.png');
    }
}
