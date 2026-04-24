<?php

namespace App\Repositories\Auth;

use App\DTOs\Auth\RegisterDTO;
use App\Models\User;

class AuthRepository implements AuthRepositoryInterface
{
    public function findByEmailOrFail(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function createUser(RegisterDTO $registerDTO): User
    {
        return User::create($registerDTO->toArray());
    }
}
