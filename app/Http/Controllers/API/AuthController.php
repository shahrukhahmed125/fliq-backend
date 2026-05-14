<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        return response()->json(
            $this->authService->register(
                $request->validated()
            ),
            201
        );
    }

    public function login(LoginRequest $request)
    {
        return response()->json(
            $this->authService->login(
                $request->validated()
            ),
            200
        );
    }

    public function logout()
    {
        return response()->json(
            $this->authService->logout(),
            200
        );
    }
}
