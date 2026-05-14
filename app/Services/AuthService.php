<?php

namespace App\Services;
use App\Repositories\Interfaces\AuthRepositoryInterface;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $data)
    {
        $user = $this->authRepository->register($data);
        return [
            'status' => true,
            'message' => 'Registered successful',
            'token' => $user->createToken('auth_token')->plainTextToken,
            'data' => $user
        ];
    }

    public function login(array $credentials)
    {
        $user = $this->authRepository->login($credentials);
        return [
            'status' => true,
            'message' => 'Login successful',
            'token' => $user->createToken('auth_token')->plainTextToken,
            'data' => $user
        ];
    }

    public function logout()
    {
        $this->authRepository->logout();

        return [
            'status' => true,
            'message' => 'Logged out successfully',
        ];
    }
}