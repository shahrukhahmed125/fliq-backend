<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Repost extends Model
{
    protected $table = 'reposts';

    protected $fillable = [
        'user_id',
        'post_id'
    ];

    protected static function booted()
    {
        static::creating(function ($repost) {
            if (empty($repost->uuid)) {
                $repost->uuid = (string) Str::uuid();
            }
        });
    }
}
