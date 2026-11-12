<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        if (empty($data['email']) || empty($data['password'])) {
            return response()->json(['message' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'Invalid email'], Response::HTTP_BAD_REQUEST);
        }

        if (strlen($data['password']) < 6) {
            return response()->json(['message' => 'Password must be at least 6 characters'], Response::HTTP_BAD_REQUEST);
        }

        if (User::where('email', $data['email'])->exists()) {
            return response()->json(['message' => 'User with this email already exists'], Response::HTTP_CONFLICT);
        }

        $user = DB::transaction(function () use ($data) {
            $user = new User();

            $user->fill([
                'name'     => $data['name'] ?? $data['email'],
                'email'    => $data['email'],
                'password' => $data['password'],
                'role'     => User::ROLE_CLIENT
            ]);

            $user->save();

            return $user;
        });

        return response()->json($user, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $credentials = [
            'email'    => $data['email'] ?? '',
            'password' => $data['password'] ?? ''
        ];

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(['token' => $token], Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user(), Response::HTTP_OK);
    }

}
