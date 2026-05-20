<?php
namespace App\Services;

use App\Repositories\Eloquent\CommentRepository;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function createComment(array $data): array
    {
       $comment =  $this->commentRepository->create($data);

        return [
            'status' => true,
            'message' => 'Comment created successfully',
            'data' => $comment->load('user'),
        ];
    }

    public function getCommentsForPost(String $uuid): array
    {
        $comments = $this->commentRepository->getPostComments($uuid);

        return [
            'status' => true,
            'message' => 'Comments retrieved successfully',
            'data' => $comments,
        ];
    }

    public function like(string $uuid): array
    {
        $like = $this->commentRepository->toggleLike($uuid);

        return [
            'status' => true,
            'message' => 'Comment like toggled successfully',
            'data' => $like,
        ];
    }

    public function deleteComment(String $uuid): void
    {
        $this->commentRepository->delete($uuid);
    }
}