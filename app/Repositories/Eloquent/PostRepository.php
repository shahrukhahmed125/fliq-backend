<?php
namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    public function all()
    {
        $posts = Post::all();

        return $posts;
    }

    public function find(String $uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->first();
        return $post;
    }

    public function store(array $data)
    {
        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $data['content'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'repost_of' => $data['repost_of'] ?? null,
            'is_repost' => $data['is_repost'] ?? false,
        ]);

        return $post;
    }

    public function update(String $uuid, array $data)
    {
        $post = Post::query()->where('uuid', $uuid)->where('user_id', auth()->id())->firstOrFail();
        $post->update([
            'user_id' => auth()->id(),
            'content' => $data['content'] ?? $post->content,
            'parent_id' => $data['parent_id'] ?? $post->parent_id,
            'repost_of' => $data['repost_of'] ?? $post->repost_of,
            'is_repost' => $data['is_repost'] ?? $post->is_repost,
        ]);
        return $post;
    }

    public function delete(String $uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->where('user_id', auth()->id())->firstOrFail();
        
        $post->delete();
    }
}