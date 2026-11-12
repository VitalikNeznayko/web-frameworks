<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Booking;
use App\Models\Guest;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{

    public const REQUIRED_FIELDS = ['guest_id', 'bed_id', 'check_in', 'check_out', 'total_price'];

    /**
     * @return JsonResponse
     */
    public function getBookings(): JsonResponse
    {
        $bookings = Booking::all();

        return response()->json($bookings, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function getBookingById(string $id): JsonResponse
    {
        $booking = Booking::find($id);

        if (empty($booking)) {
            return response()->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($booking, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createBooking(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $missingFields = array_diff(self::REQUIRED_FIELDS, array_keys($data));

        if (!empty($missingFields)) {
            return response()->json(
                ['message' => 'Missing required fields: ' . implode(', ', $missingFields)],
                Response::HTTP_BAD_REQUEST
            );
        }

        $error = $this->checkBookingData($data);

        if (!empty($error)) {
            return $error;
        }

        $booking = DB::transaction(function () use ($data) {
            $booking = new Booking();

            $booking->fill($data);

            $booking->save();

            return $booking;
        });

        return response()->json($booking->refresh(), Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateBookingById(string $id, Request $request): JsonResponse
    {
        $booking = Booking::find($id);

        if (empty($booking)) {
            return response()->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true) ?? [];

        $error = $this->checkBookingData($data);

        if (!empty($error)) {
            return $error;
        }

        $booking->fill($data);

        $booking->save();

        return response()->json($booking, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteBookingById(string $id): JsonResponse
    {
        $booking = Booking::find($id);

        if (empty($booking)) {
            return response()->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        $booking->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param array $data
     * @return JsonResponse|null
     */
    private function checkBookingData(array $data): ?JsonResponse
    {
        if (isset($data['guest_id']) && empty(Guest::find($data['guest_id']))) {
            return response()->json(['message' => 'Guest not found'], Response::HTTP_NOT_FOUND);
        }

        if (isset($data['bed_id']) && empty(Bed::find($data['bed_id']))) {
            return response()->json(['message' => 'Bed not found'], Response::HTTP_NOT_FOUND);
        }

        foreach (['check_in', 'check_out'] as $field) {
            if (isset($data[$field]) && !DateTime::createFromFormat('!Y-m-d', $data[$field])) {
                return response()->json(['message' => 'Dates must be in format Y-m-d'], Response::HTTP_BAD_REQUEST);
            }
        }

        return null;
    }

}
