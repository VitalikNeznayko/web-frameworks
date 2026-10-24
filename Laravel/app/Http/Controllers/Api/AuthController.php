<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $token = Auth::guard('api')->attempt($request->validated());

        if (! $token) {
            return response()->json(['message' => 'Невірний email або пароль.'], 401);
        }

        return $this->tokenResponse($token);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            ...$user->toArray(),
            'role_label' => $user->roleLabel(),
        ]);
    }

    public function refresh(): JsonResponse
    {
        return $this->tokenResponse(Auth::guard('api')->refresh());
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Ви вийшли з системи.']);
    }

    private function tokenResponse(string $token): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ]);
    }
}
