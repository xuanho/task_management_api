<?php

namespace App\Interfaces\Auth;

use App\Models\RefreshToken;

interface RefreshTokenRepositoryInterface
{
    public function create(array $data): void;

    public function findValid(string $hashedToken): ?RefreshToken;

    public function revoke(int $id): void;

    public function revokeAllByUserId(int $userId): void;
}
