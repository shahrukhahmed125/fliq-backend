<?php
namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public function create(array $data)
    {
        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $data['post_id'],
            'content' => $data['content'],
        ]);

        return $comment;
    }

    public function getPostComments(String $uuid)
    {
        $comments = Comment::query()
        ->with('user')
        ->latest()
        ->get();

        return $comments;
    }

    public function delete(String $uuid)
    {
        $comment = Comment::query()->where('uuid', $uuid)->where('user_id', auth()->id())->firstOrFail();

        $comment->delete();
    }
}