<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}
