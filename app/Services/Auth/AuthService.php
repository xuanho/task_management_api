<?php

namespace App\Services\Auth;

use App\DTOs\Auth\AuthResponseDTO;
use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Exceptions\ApiException;
use App\Exceptions\UnauthorizedException;
use App\Http\Resources\UserResource;
use App\Interfaces\Auth\RefreshTokenRepositoryInterface;
use App\Interfaces\Auth\TokenServiceInterface;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(private AuthRepository $authRepository,
        private TokenServiceInterface $tokenService,
        private RefreshTokenRepositoryInterface $refreshRepo) {}

    public function login(LoginDTO $loginDTO): AuthResponseDTO
    {
        $user = $this->authRepository->findByEmailOrFail($loginDTO->getEmail());
        if (! $user || ! Hash::check($loginDTO->getPassword(), $user->password)) {
            throw new UnauthorizedException;
        }
        $access_token = $this->tokenService->generateAccessToken($user);
        $refresh_token = Str::random(64);

        // save refresh token to database
        $this->refreshRepo->create([
            'user_id' => $user->id,
            'token' => hash('sha256', $refresh_token),
            'expires_at' => now()->addDays(7),
            'revoked' => false,
        ]);

        return new AuthResponseDTO($access_token, 'Bearer', new UserResource($user), $refresh_token);

    }

    public function register(RegisterDTO $registerDTO)
    {
        return DB::transaction(function () use ($registerDTO) {
            $user = $this->authRepository->findByEmail($registerDTO->getEmail());
            if ($user) {
                throw new ApiException('User already exists', 'AUTH_USER_ALREADY_EXISTS', 400);
            }
            $user = $this->authRepository->createUser($registerDTO);

            return new AuthResponseDTO($access_token, 'Bearer', new UserResource($user));
        });

    }

    public function refresh(string $refresh_token)
    {
        $hashed = hash('sha256', $refresh_token);
        $recode = $this->refreshRepo->findValid($hashed);
        if (! $recode) {
            throw new UnauthorizedException('Invalid refresh token');
        }

        // rotate refresh token
        $this->refreshRepo->revoke($recode->id);
        $new_access_token = $this->tokenService->generateAccessToken($recode->user);
        $new_refresh_token = Str::random(64);

        $this->refreshRepo->create([
            'user_id' => $recode->user->id,
            'token' => hash('sha256', $new_refresh_token),
            'expires_at' => now()->addDays(7),
            'revoked' => false,
        ]);

        return new AuthResponseDTO($new_access_token, 'Bearer', new UserResource($recode->user), $new_refresh_token);

    }

    public function logout()
    {
        $this->refreshRepo->revokeAllByUserId(auth()->id());
        $this->tokenService->invalidateToken();

        return json_encode(['message' => 'Successfully logged out']);
    }
}
