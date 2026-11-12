<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BedController extends Controller
{

    public const REQUIRED_FIELDS = ['room_id', 'label', 'bed_type'];

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getBeds(Request $request): JsonResponse
    {
        $queryParams = $request->query();

        $beds = Bed::getFilteredItems($queryParams);

        return response()->json($beds, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function getBedById(string $id): JsonResponse
    {
        $bed = Bed::find($id);

        if (empty($bed)) {
            return response()->json(['message' => 'Bed not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($bed, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createBed(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $missingFields = array_diff(self::REQUIRED_FIELDS, array_keys($data));

        if (!empty($missingFields)) {
            return response()->json(
                ['message' => 'Missing required fields: ' . implode(', ', $missingFields)],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (empty(Room::find($data['room_id']))) {
            return response()->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }

        $bed = DB::transaction(function () use ($data) {
            $bed = new Bed();

            $bed->fill($data);

            $bed->save();

            return $bed;
        });

        return response()->json($bed->refresh(), Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateBedById(string $id, Request $request): JsonResponse
    {
        $bed = Bed::find($id);

        if (empty($bed)) {
            return response()->json(['message' => 'Bed not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true) ?? [];

        if (isset($data['room_id']) && empty(Room::find($data['room_id']))) {
            return response()->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }

        $bed->fill($data);

        $bed->save();

        return response()->json($bed, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteBedById(string $id): JsonResponse
    {
        $bed = Bed::find($id);

        if (empty($bed)) {
            return response()->json(['message' => 'Bed not found'], Response::HTTP_NOT_FOUND);
        }

        $bed->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

}
