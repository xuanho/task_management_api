<?php

namespace App\DTOs\Auth;

use App\Http\Resources\UserResource;

class AuthResponseDTO
{
    public function __construct(public string $access_token, public string $token_type, public UserResource $user) {}
}
