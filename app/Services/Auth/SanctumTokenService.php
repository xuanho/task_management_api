<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\TokenServiceInterface;

class SanctumTokenService implements TokenServiceInterface
{
    public function generateAccessToken($user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function invalidateToken(): void
    {
        request->user()->currentAccessToken()->delete();
    }
}
