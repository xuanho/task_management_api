<?php

namespace App\Repositories\Auth;

use App\Interfaces\Auth\RefreshTokenRepositoryInterface;
use App\Models\RefreshToken;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function create(array $data): void
    {
        RefreshToken::create($data);
    }

    public function findValid(string $hashedToken): ?RefreshToken
    {
        return RefreshToken::where('token', $hashedToken)
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->first();

    }

    public function revoke(int $id): void
    {
        $token = RefreshToken::find($id);
        if ($token) {
            $token->revoked = true;
            $token->save();
        }

    }

    public function revokeAllByUserId(int $userId): void
    {
        RefreshToken::where('user_id', $userId)->update(['revoked' => true]);
    }
}
