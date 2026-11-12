<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{

    public const REQUIRED_FIELDS = ['booking_id', 'amount', 'method'];

    /**
     * @return JsonResponse
     */
    public function getPayments(): JsonResponse
    {
        $payments = Payment::all();

        return response()->json($payments, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function getPaymentById(string $id): JsonResponse
    {
        $payment = Payment::find($id);

        if (empty($payment)) {
            return response()->json(['message' => 'Payment not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($payment, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createPayment(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $missingFields = array_diff(self::REQUIRED_FIELDS, array_keys($data));

        if (!empty($missingFields)) {
            return response()->json(
                ['message' => 'Missing required fields: ' . implode(', ', $missingFields)],
                Response::HTTP_BAD_REQUEST
            );
        }

        $error = $this->checkPaymentData($data);

        if (!empty($error)) {
            return $error;
        }

        $payment = DB::transaction(function () use ($data) {
            $payment = new Payment();

            $payment->fill($data);

            $payment->save();

            return $payment;
        });

        return response()->json($payment->refresh(), Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePaymentById(string $id, Request $request): JsonResponse
    {
        $payment = Payment::find($id);

        if (empty($payment)) {
            return response()->json(['message' => 'Payment not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true) ?? [];

        $error = $this->checkPaymentData($data);

        if (!empty($error)) {
            return $error;
        }

        $payment->fill($data);

        $payment->save();

        return response()->json($payment, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deletePaymentById(string $id): JsonResponse
    {
        $payment = Payment::find($id);

        if (empty($payment)) {
            return response()->json(['message' => 'Payment not found'], Response::HTTP_NOT_FOUND);
        }

        $payment->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param array $data
     * @return JsonResponse|null
     */
    private function checkPaymentData(array $data): ?JsonResponse
    {
        if (isset($data['booking_id']) && empty(Booking::find($data['booking_id']))) {
            return response()->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        if (!empty($data['paid_at']) && !DateTime::createFromFormat('Y-m-d H:i:s', $data['paid_at'])) {
            return response()->json(['message' => 'paid_at must be in format Y-m-d H:i:s'], Response::HTTP_BAD_REQUEST);
        }

        return null;
    }

}
