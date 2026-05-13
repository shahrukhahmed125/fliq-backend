<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CommentLike extends Model
{
    protected $table = 'comment_likes';

    protected $fillable = [
        'user_id',
        'comment_id'
    ];

    protected static function booted()
    {
        static::creating(function ($commentLike) {
            if (empty($commentLike->uuid)) {
                $commentLike->uuid = (string) Str::uuid();
            }
        });
    }
}
