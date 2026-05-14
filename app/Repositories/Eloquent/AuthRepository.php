<?php

namespace App\Repositories\Eloquent;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository implements AuthRepositoryInterface
{
    
    public function register(array $data)
    {
        $baseName = Str::slug($data['name']);
        $username = '@' . $baseName;

        while (
            User::query()
                ->where('username', $username)
                ->exists()
        ) {
            $username = '@' . $baseName . rand(10, 9999);
        }

        $user = User::create([
            'name' => $data['name'],
            'username' => $username,
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        return $user;
    }

    public function login(array $credentials)
    {
        $user = User::query()->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        return $user;
    }

    public function logout(): void
    {
        auth()->user()->tokens()->delete();
    }
}
