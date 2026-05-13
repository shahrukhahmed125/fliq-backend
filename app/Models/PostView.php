<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostView extends Model
{
    protected $table = 'post_views';
    
    protected $fillable = [
        'user_id',
        'post_id',
        'ip_address',
        'user_agent'
    ];

    protected static function booted()
    {
        static::creating(function ($postView) {
            if (empty($postView->uuid)) {
                $postView->uuid = (string) Str::uuid();
            }
        });
    }


}
