<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends Controller
{

    public const REQUIRED_FIELDS = ['number', 'type', 'floor', 'price_per_night'];

    /**
     * @return JsonResponse
     */
    public function getRooms(): JsonResponse
    {
        $rooms = Room::all();

        return response()->json($rooms, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function getRoomById(string $id): JsonResponse
    {
        $room = Room::find($id);

        if (empty($room)) {
            return response()->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($room, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createRoom(Request $request): JsonResponse
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
            $room = DB::transaction(function () use ($data) {
                $room = new Room();

                $room->fill($data);

                $room->save();

                return $room;
            });
        } catch (UniqueConstraintViolationException) {
            return response()->json(['message' => 'Room with this number already exists'], Response::HTTP_CONFLICT);
        }

        return response()->json($room->refresh(), Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateRoomById(string $id, Request $request): JsonResponse
    {
        $room = Room::find($id);

        if (empty($room)) {
            return response()->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true) ?? [];

        $room->fill($data);

        try {
            $room->save();
        } catch (UniqueConstraintViolationException) {
            return response()->json(['message' => 'Room with this number already exists'], Response::HTTP_CONFLICT);
        }

        return response()->json($room, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteRoomById(string $id): JsonResponse
    {
        $room = Room::find($id);

        if (empty($room)) {
            return response()->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }

        $room->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

}
