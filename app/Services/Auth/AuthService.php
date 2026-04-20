<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\AuthResponseDTO;
use App\Exceptions\UnauthorizedException;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\DTOs\Auth\RegisterDTO;
use App\Exceptions\ApiException;
class AuthService
{
    // tim loi AuthResponseDTO not found
    public function __construct(private AuthRepository $authRepository)
    {
    }
    public function login(LoginDTO $loginDTO): AuthResponseDTO
    {
        $user = $this->authRepository->findByEmailOrFail($loginDTO->getEmail());
        if(!$user || !Hash::check($loginDTO->getPassword(), $user->password)) {
            throw new UnauthorizedException();
        }
        $token =$user->createToken('auth_token')->plainTextToken;
        return new AuthResponseDTO($token, 'Bearer', new UserResource($user));
    }
    public function register(RegisterDTO $registerDTO){
        $user = $this->authRepository->findByEmail($registerDTO->getEmail());
        if($user){
            throw new ApiException('User already exists','AUTH_USER_ALREADY_EXISTS', 400);
        }
        $user = $this->authRepository->createUser($registerDTO);
        $token = $user->createToken('auth_token')->plainTextToken;
        return new AuthResponseDTO($token, 'Bearer', new UserResource($user));
    }
}