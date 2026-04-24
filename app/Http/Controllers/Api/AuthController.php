<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $dto = LoginDTO::fromArray($request->validated());

        return response()->json($this->authService->login($dto));
    }

    public function register(RegisterRequest $request)
    {
        $dto = RegisterDTO::fromArray($request->validated());

        return response()->json($this->authService->register($dto), 201);
    }

    public function logout(Request $request)
    {
        // xóa token từ request
        // trả về response
    }
}
