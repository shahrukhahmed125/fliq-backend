<?php
namespace App\Services;

use App\Repositories\Interfaces\PostRepositoryInterface;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function all(): array
    {
        $posts = $this->postRepository->all();

        return [
            'status' => true,
            'message' => 'Posts retrieved successfully',
            'data' => $posts,
        ];
    }

    public function find(String $uuid): array
    {
        $post = $this->postRepository->find($uuid);

        if (!$post) {
            return [
                'status' => false,
                'message' => 'Post not found',
                'data' => null,
            ];
        }

        return [
            'status' => true,
            'message' => 'Post retrieved successfully',
            'data' => $post,
        ];
    }

    public function store(array $data, $files = null): array
    {
        $post = $this->postRepository->store($data);
        
        // YOU MUST MANUALLY CALL THIS
        if (!empty($files)) {
            $this->postRepository->attachMedia($post, $files);
        }

        return [
            'status' => true,
            'message' => 'Post created successfully',
            'data' => $post->load('media'),
        ];
    }

    public function update(String $uuid, array $data): array
    {
        $post = $this->postRepository->update($uuid, $data);

        return [
            'status' => true,
            'message' => 'Post updated successfully',
            'data' => $post,
        ];
    }

    public function like(string $uuid): array
    {
        $like = $this->postRepository->toggleLike($uuid);

        return [
            'status' => true,
            'message' => 'Post like toggled successfully',
            'data' => $like,
        ];
    }

    public function delete(String $uuid): void
    {
        $this->postRepository->delete($uuid);
    }
}
