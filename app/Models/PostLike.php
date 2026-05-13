<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostLike extends Model
{
    protected $table = 'post_likes';

    protected $fillable = [
        'user_id',
        'post_id'
    ];

    protected static function booted()
    {
        static::creating(function ($postLike) {
            if (empty($postLike->uuid)) {
                $postLike->uuid = (string) Str::uuid();
            }
        });
    }
}
