<?php

namespace App\Interfaces\Auth;

interface TokenServiceInterface
{
    public function generateAccessToken($user): string;

    public function invalidateToken(): void;
}
