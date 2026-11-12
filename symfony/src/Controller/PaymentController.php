<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Payment;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class PaymentController extends AbstractController
{

    public const REQUIRED_FIELDS = ['booking_id', 'amount', 'method'];

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/payments', name: 'app_get_payments', methods: ['GET'])]
    public function getPayments(): JsonResponse
    {
        $payments = $this->entityManager->getRepository(Payment::class)->findAll();

        return $this->json($payments, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/payments/{id}', name: 'app_get_payment_by_id', methods: ['GET'])]
    public function getPaymentById(string $id): JsonResponse
    {
        /** @var Payment $payment */
        $payment = $this->entityManager->getRepository(Payment::class)->find($id);

        if (empty($payment)) {
            return $this->json(['message' => 'Payment not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($payment, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/payments', name: 'app_create_payment', methods: ['POST'])]
    public function createPayment(Request $request): JsonResponse
    {
        $paymentData = json_decode($request->getContent(), true) ?? [];

        $missingFields = array_diff(self::REQUIRED_FIELDS, array_keys($paymentData));

        if (!empty($missingFields)) {
            return $this->json(
                ['message' => 'Missing required fields: ' . implode(', ', $missingFields)],
                Response::HTTP_BAD_REQUEST
            );
        }

        /** @var Booking $booking */
        $booking = $this->entityManager->getRepository(Booking::class)->find($paymentData['booking_id']);

        if (empty($booking)) {
            return $this->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        $paidAt = null;

        if (!empty($paymentData['paid_at'])) {
            $paidAt = DateTime::createFromFormat('Y-m-d H:i:s', $paymentData['paid_at']);

            if (!$paidAt) {
                return $this->json(['message' => 'paid_at must be in format Y-m-d H:i:s'], Response::HTTP_BAD_REQUEST);
            }
        }

        $payment = new Payment();

        $payment
            ->setBooking($booking)
            ->setAmount((string)$paymentData['amount'])
            ->setMethod($paymentData['method'])
            ->setStatus($paymentData['status'] ?? 'pending')
            ->setPaidAt($paidAt);

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        return $this->json($payment, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/payments/{id}', name: 'app_update_payment_by_id', methods: ['PATCH'])]
    public function updatePaymentById(string $id, Request $request): JsonResponse
    {
        /** @var Payment $payment */
        $payment = $this->entityManager->getRepository(Payment::class)->find($id);

        if (empty($payment)) {
            return $this->json(['message' => 'Payment not found'], Response::HTTP_NOT_FOUND);
        }

        $paymentData = json_decode($request->getContent(), true) ?? [];

        if (isset($paymentData['booking_id'])) {
            /** @var Booking $booking */
            $booking = $this->entityManager->getRepository(Booking::class)->find($paymentData['booking_id']);

            if (empty($booking)) {
                return $this->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
            }

            $payment->setBooking($booking);
        }

        if (isset($paymentData['amount'])) {
            $payment->setAmount((string)$paymentData['amount']);
        }

        if (isset($paymentData['method'])) {
            $payment->setMethod($paymentData['method']);
        }

        if (isset($paymentData['status'])) {
            $payment->setStatus($paymentData['status']);
        }

        if (isset($paymentData['paid_at'])) {
            $paidAt = DateTime::createFromFormat('Y-m-d H:i:s', $paymentData['paid_at']);

            if (!$paidAt) {
                return $this->json(['message' => 'paid_at must be in format Y-m-d H:i:s'], Response::HTTP_BAD_REQUEST);
            }

            $payment->setPaidAt($paidAt);
        }

        $this->entityManager->flush();

        return $this->json($payment, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/payments/{id}', name: 'app_delete_payment_by_id', methods: ['DELETE'])]
    public function deletePaymentById(string $id): JsonResponse
    {
        /** @var Payment $payment */
        $payment = $this->entityManager->getRepository(Payment::class)->find($id);

        if (empty($payment)) {
            return $this->json(['message' => 'Payment not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($payment);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

}
