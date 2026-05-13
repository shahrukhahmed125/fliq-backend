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

    public function getRegister(array $data)
    {
        return $this->authRepository->register($data);
    }

    public function getLogin(array $credentials)
    {
        return $this->authRepository->login($credentials);
    }

    public function getLogout()
    {
        return $this->authRepository->logout();
    }
}