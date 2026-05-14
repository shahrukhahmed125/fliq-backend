<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'access_token' => ['required']
        ]);

        $response = Http::get(
            'https://www.googleapis.com/oauth2/v3/userinfo',
            [
                'access_token' => $request->access_token
            ]
        );

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Invalid Google token'
            ], 401);
        }

        $googleUser = $response->json();

        $user = User::query()->firstOrCreate(
            [
                'email' => $googleUser['email']
            ],
            [
                'name' => $googleUser['name'],
                'username' => '@'.Str::slug($googleUser['name']) . rand(100, 999),
                'password' => Str::random(24),
                'email_verified_at' => now(),
                'google_id' => $googleUser['sub'],
                'profile_photo' => $googleUser['picture'] ?? null,
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }
}
