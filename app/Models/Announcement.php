<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Announcement extends Model
{

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
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($announcement) {
            $announcement->slug = Str::slug($announcement->title);
        });
    }

}
