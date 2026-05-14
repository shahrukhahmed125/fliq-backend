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

    public function store(array $data): array
    {
        $post = $this->postRepository->store($data);

        return [
            'status' => true,
            'message' => 'Post created successfully',
            'data' => $post,
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

    public function delete(String $uuid): void
    {
        $this->postRepository->delete($uuid);
    }
}
