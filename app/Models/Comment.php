<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Comment extends Model
{
    use SoftDeletes;
    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'content'
    ];

    protected static function booted()
    {
        static::creating(function ($comment) {
            if (empty($comment->uuid)) {
                $comment->uuid = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with(['user', 'replies']); // recursion
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }
}
