<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\TokenServiceInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtTokenService implements TokenServiceInterface
{
    public function generateAccessToken($user): string
    {
        return JWTAuth::claims(['type' => 'access'])->fromUser($user);
    }

    public function invalidateToken(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }
}
