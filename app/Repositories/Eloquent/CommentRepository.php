<?php
namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Post;

class CommentRepository implements CommentRepositoryInterface
{
    public function create(array $data)
    {
        $post = Post::query()->where('uuid', $data['post_uuid'])->firstOrFail();
        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'parent_id' => $data['parent_id'] ?? null,
            'content' => $data['content'],
        ]);

        return $comment;
    }

    public function getPostComments(String $uuid, $perPage = 10)
    {
        $comments = Comment::query()
        ->with(['user', 'replies.user'])
        // ->whereNull('parent_id')
        ->latest()
        ->get();

        return $comments;
    }

    public function toggleLike(string $uuid): array
    {
        $comment = Comment::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $query = CommentLike::query()
            ->where('user_id', auth()->id())
            ->where('comment_id', $comment->id);

        if ($query->exists()) {

            $query->delete();

            return ['liked' => false];
        }

        CommentLike::create([
            'user_id' => auth()->id(),
            'comment_id' => $comment->id,
        ]);

        return ['liked' => true];
    }

    public function delete(String $uuid)
    {
        $comment = Comment::query()->where('uuid', $uuid)->where('user_id', auth()->id())->firstOrFail();

        $comment->delete();
    }
}