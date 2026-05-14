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
        $post = Post::query()->where('uuid', $uuid)->first();
        if ($post) {
            $post->update([
                'content' => $data['content'] ?? $post->content,
            ]);
            return $post;
        }
        return null;
    }

    public function delete(String $uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->first();
        if ($post) {
            $post->delete();
            return true;
        }
        return false;
    }
}