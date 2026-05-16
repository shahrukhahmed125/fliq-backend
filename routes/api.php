<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\API\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [GoogleAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts/store', [PostController::class, 'store']);
    Route::get('/posts/show/{uuid}', [PostController::class, 'show']);
    Route::put('/posts/update/{uuid}', [PostController::class, 'update']);
    Route::post('/posts/{uuid}/like', [PostController::class, 'toggleLike']);
    Route::delete('/posts/delete/{uuid}', [PostController::class, 'destroy']);
    Route::get('/logout', [AuthController::class, 'logout']);

});