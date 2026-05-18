<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Media extends Model
{
    protected $fillable = [
        'file_path',
        'file_type',
        'mime_type',
        'size',
        'status',
        'thumbnail',
        'processing_error',
        'duration'

    ];

    protected static function booted()
    {
        static::creating(function ($media) {
            if (empty($media->uuid)) {
                $media->uuid = (string) Str::uuid();    
            }
         });
    }  

    public function mediable()
    {
        return $this->morphTo();
    }
}
