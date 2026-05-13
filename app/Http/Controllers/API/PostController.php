<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $data = Post::all();

        return response()->json([
            'status' => true,
            'message' => 'Posts retrieved successfully',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
                'media' => 'nullable|array',
                'media.*' => 'nullable|string',
            ]);    

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $post = Post::create([
                'content' => $request->content,
                'media' => $request->media,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Post created successfully',
                'data' => $post
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create post'
            ], 500);
        }

    }

    public function show(String $id)
    {
        $post = Post::findOrFail($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found!'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Post retrieved successfully',
            'data' => $post
        ], 200);
    }
}
