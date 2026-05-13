<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostHashtag extends Model
{
    protected $table = 'post_hashtags';

    protected $fillable = [
        'name',
        'posts_count',
        'views_count'
    ];

    protected static function booted()
    {
        static::creating(function ($postHashtag) {
            if (empty($postHashtag->uuid)) {
                $postHashtag->uuid = (string) Str::uuid();
            }
        });
    }
}
