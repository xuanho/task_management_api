<?php

namespace App\Services\Auth;

use App\DTOs\Auth\AuthResponseDTO;
use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Exceptions\ApiException;
use App\Exceptions\UnauthorizedException;
use App\Http\Resources\UserResource;
use App\Interfaces\Auth\TokenServiceInterface;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(private AuthRepository $authRepository, private TokenServiceInterface $tokenService) {}

    public function login(LoginDTO $loginDTO): AuthResponseDTO
    {
        $user = $this->authRepository->findByEmailOrFail($loginDTO->getEmail());
        if (! $user || ! Hash::check($loginDTO->getPassword(), $user->password)) {
            throw new UnauthorizedException;
        }
        $token = $this->tokenService->generateToken($user);

        return new AuthResponseDTO($token, 'Bearer', new UserResource($user));

    }

    public function register(RegisterDTO $registerDTO)
    {
        return DB::transaction(function () use ($registerDTO) {
            $user = $this->authRepository->findByEmail($registerDTO->getEmail());
            if ($user) {
                throw new ApiException('User already exists', 'AUTH_USER_ALREADY_EXISTS', 400);
            }
            $user = $this->authRepository->createUser($registerDTO);
            $token = $this->tokenService->generateToken($user);

            return new AuthResponseDTO($token, 'Bearer', new UserResource($user));
        });

    }

    public function logout()
    {
        $this->tokenService->invalidateToken();

        return json_encode(['message' => 'Successfully logged out']);
    }
}
