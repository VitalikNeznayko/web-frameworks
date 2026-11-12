<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function getUsers(): JsonResponse
    {
        $users = User::all();

        return response()->json($users, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUserRole(string $id, Request $request): JsonResponse
    {
        $user = User::find($id);

        if (empty($user)) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true) ?? [];
        $roles = array_keys(User::ROLE_LEVELS);

        if (empty($data['role']) || !in_array($data['role'], $roles, true)) {
            return response()->json(
                ['message' => 'Role must be one of: ' . implode(', ', $roles)],
                Response::HTTP_BAD_REQUEST
            );
        }

        $user->role = $data['role'];

        $user->save();

        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteUserById(string $id): JsonResponse
    {
        $user = User::find($id);

        if (empty($user)) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        if ($user->id === auth('api')->id()) {
            return response()->json(['message' => 'You cannot delete yourself'], Response::HTTP_BAD_REQUEST);
        }

        $user->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

}
