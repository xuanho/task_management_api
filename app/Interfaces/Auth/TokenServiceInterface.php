<?php

namespace App\Interfaces\Auth;

interface TokenServiceInterface
{
    public function generateToken($user): string;

    public function invalidateToken(): void;
}
