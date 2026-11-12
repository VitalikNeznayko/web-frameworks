<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class GuestController extends Controller
{

    public const REQUIRED_FIELDS = ['first_name', 'last_name', 'email', 'phone', 'passport_number', 'country'];

    /**
     * @return JsonResponse
     */
    public function getGuests(): JsonResponse
    {
        $guests = Guest::all();

        return response()->json($guests, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function getGuestById(string $id): JsonResponse
    {
        $guest = Guest::find($id);

        if (empty($guest)) {
            return response()->json(['message' => 'Guest not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($guest, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createGuest(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $missingFields = array_diff(self::REQUIRED_FIELDS, array_keys($data));

        if (!empty($missingFields)) {
            return response()->json(
                ['message' => 'Missing required fields: ' . implode(', ', $missingFields)],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $guest = DB::transaction(function () use ($data) {
                $guest = new Guest();

                $guest->fill($data);

                $guest->save();

                return $guest;
            });
        } catch (UniqueConstraintViolationException) {
            return response()->json(['message' => 'Guest with this email already exists'], Response::HTTP_CONFLICT);
        }

        return response()->json($guest->refresh(), Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateGuestById(string $id, Request $request): JsonResponse
    {
        $guest = Guest::find($id);

        if (empty($guest)) {
            return response()->json(['message' => 'Guest not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true) ?? [];

        $guest->fill($data);

        try {
            $guest->save();
        } catch (UniqueConstraintViolationException) {
            return response()->json(['message' => 'Guest with this email already exists'], Response::HTTP_CONFLICT);
        }

        return response()->json($guest, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteGuestById(string $id): JsonResponse
    {
        $guest = Guest::find($id);

        if (empty($guest)) {
            return response()->json(['message' => 'Guest not found'], Response::HTTP_NOT_FOUND);
        }

        $guest->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

}
