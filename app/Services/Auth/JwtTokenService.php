<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\TokenServiceInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtTokenService implements TokenServiceInterface
{
    public function generateToken($user): string
    {
        return JWTAuth::fromUser($user);
    }

    public function invalidateToken(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }
}
