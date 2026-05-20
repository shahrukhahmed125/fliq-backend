<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(String $postUuid)
    {
        return response()->json(
            $this->commentService->getCommentsForPost($postUuid),
        200);
    }

    public function store(CommentRequest $request)
    {
        return response()->json(
            $this->commentService->createComment($request->validated()), 
        201);
    }

    public function toggleLike(String $uuid)
    {
        return response()->json(
            $this->commentService->like($uuid),
        200);
    }

    public function destroy(String $uuid)
    {
        $this->commentService->deleteComment($uuid);

        return response()->json([
            'status' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
}
