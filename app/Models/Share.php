<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Share extends Model
{
    protected $table = 'shares';

    protected $fillable = [
        'user_id',
        'post_id',
        'platform'
    ];

    protected static function booted()
    {
        static::creating(function ($share) {
            if (empty($share->uuid)) {
                $share->uuid = (string) Str::uuid();
            }
        });
    }
}
