<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Services\PostService;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        return response()->json(
            $this->postService->all(),
        200);
    }

    public function store(StorePostRequest $request)
    {
        return response()->json(
            $this->postService->store($request->validated()), 
        201);
    }

    public function show(String $uuid)
    {
        return response()->json(
            $this->postService->find($uuid),
        200);
    }

    public function update(String $uuid, StorePostRequest $request)
    {
        return response()->json(
            $this->postService->update($uuid, $request->validated()),
        200);
    }

    public function destroy(String $uuid)
    {
        $this->postService->delete($uuid);

        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully',
        ]);
    }


}
