<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\DTOs\Auth\RegisterDTO;
class AuthRepository implements AuthRepositoryInterface
{
    public function findByEmailOrFail(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }
    public function findByEmail(string $email): User|null
    {
        return User::where('email', $email)->first();
    }

    public function createUser(RegisterDTO $registerDTO): User
    {
        return User::create($registerDTO->toArray());
    }
}
