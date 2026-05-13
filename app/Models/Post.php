<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'posts';

    protected $fillable = [
        'user_id',
        'content',
        'media',
        'parent_id',
        'repost_of',
        'is_repost',
    ];

    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->uuid)) {
                $post->uuid = (string) Str::uuid();
            }
        });
    }
}
