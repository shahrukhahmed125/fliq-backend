<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bookmark extends Model
{
    protected $table = 'bookmarks';

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    protected static function booted()
    {
        static::creating(function ($bookmark) {
            if (empty($bookmark->uuid)) {
                $bookmark->uuid = (string) Str::uuid();
            }
        });
    }
}
