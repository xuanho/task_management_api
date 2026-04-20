<?php

namespace App\Repositories\Auth;

use App\Models\User;
interface AuthRepositoryInterface
{
    public function findByEmail(string $email): User|null;
    public function findByEmailOrFail(string $email): User;
}
