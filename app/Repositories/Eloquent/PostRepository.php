<?php
namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Models\PostLike;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\Media\VideoUploadService;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class PostRepository implements PostRepositoryInterface
{
    public function all()
    {
        $userId = auth()->id();

        // get post ids user liked in ONE query
        $likedPostIds = [];

        if ($userId) {
            $likedPostIds = PostLike::query()->where('user_id', $userId)
                ->pluck('post_id')
                ->toArray();
        }

        $posts = Post::with(['user', 'media'])
            ->withCount('likes')
            ->latest()
            ->get()
            ->map(function ($post) use ($likedPostIds) {
                $post->is_liked = in_array($post->id, $likedPostIds);
                return $post;
            });

        return $posts;
    }

    public function find(String $uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->with(['user', 'media'])->first();
        return $post;
    }

    public function store(array $data)
    {
        $parent = Post::query()->where('uuid', $data['parent_id'])->first();
        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $data['content'] ?? null,
            'parent_id' => $parent ? $parent->id : null,
            'quote_id' => $data['quote_id'] ?? null,
            'repost_of' => $data['repost_of'] ?? null,
        ]);

        return $post;
    }

    public function attachMedia(Post $post, $files)
    {
        $manager = new ImageManager(new Driver());

        foreach ((array) $files as $file) {

            if (str_contains($file->getMimeType(), 'image')) {

                $image = $manager->read($file);

                $image->scale(width: 1280);

                $encoded = $image->toJpeg(75)->toString();

                $filename = Str::uuid() . '.jpg';

                $path = 'posts/' . $filename;

                Storage::disk('public')->put($path, $encoded);

                $post->media()->create([
                    'file_path' => $path,
                    'file_type' => 'image',
                    'mime_type' => $file->getMimeType(),
                    'size' => Storage::disk('public')->size($path),
                    'status' => 'completed',
                ]);
            }else {

                app(VideoUploadService::class)
                    ->upload($post, $file);
            }
        }
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

    public function toggleLike(string $uuid): array
    {
        $post = Post::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $query = PostLike::query()
            ->where('user_id', auth()->id())
            ->where('post_id', $post->id);

        if ($query->exists()) {

            $query->delete();

            return ['liked' => false];
        }

        PostLike::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
        ]);

        return ['liked' => true];
    }

    public function delete(String $uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->where('user_id', auth()->id())->firstOrFail();
        
        $post->delete();
    }

    public function replies(String $uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->firstOrFail();

        $replies = Post::with(['user', 'media'])
            ->withCount('likes')
            ->where('parent_id', $post->id)
            ->latest()
            ->get();

        return $replies;
    }
}