<?php

namespace App\Controller;

use App\Entity\Bed;
use App\Entity\Booking;
use App\Entity\Guest;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class BookingController extends AbstractController
{

    public const REQUIRED_FIELDS = ['guest_id', 'bed_id', 'check_in', 'check_out', 'total_price'];

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
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/bookings', name: 'app_get_bookings', methods: ['GET'])]
    public function getBookings(Request $request): JsonResponse
    {
        $queryParams = $request->query->all();

        $bookings = $this->entityManager->getRepository(Booking::class)->getFilteredItems($queryParams);

        return $this->json($bookings, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/bookings/{id}', name: 'app_get_booking_by_id', methods: ['GET'])]
    public function getBookingById(string $id): JsonResponse
    {
        /** @var Booking $booking */
        $booking = $this->entityManager->getRepository(Booking::class)->find($id);

        if (empty($booking)) {
            return $this->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($booking, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/bookings', name: 'app_create_booking', methods: ['POST'])]
    public function createBooking(Request $request): JsonResponse
    {
        $bookingData = json_decode($request->getContent(), true) ?? [];

        $missingFields = array_diff(self::REQUIRED_FIELDS, array_keys($bookingData));

        if (!empty($missingFields)) {
            return $this->json(
                ['message' => 'Missing required fields: ' . implode(', ', $missingFields)],
                Response::HTTP_BAD_REQUEST
            );
        }

        /** @var Guest $guest */
        $guest = $this->entityManager->getRepository(Guest::class)->find($bookingData['guest_id']);

        if (empty($guest)) {
            return $this->json(['message' => 'Guest not found'], Response::HTTP_NOT_FOUND);
        }

        /** @var Bed $bed */
        $bed = $this->entityManager->getRepository(Bed::class)->find($bookingData['bed_id']);

        if (empty($bed)) {
            return $this->json(['message' => 'Bed not found'], Response::HTTP_NOT_FOUND);
        }

        $checkIn = DateTime::createFromFormat('!Y-m-d', $bookingData['check_in']);
        $checkOut = DateTime::createFromFormat('!Y-m-d', $bookingData['check_out']);

        if (!$checkIn || !$checkOut) {
            return $this->json(['message' => 'Dates must be in format Y-m-d'], Response::HTTP_BAD_REQUEST);
        }

        $booking = new Booking();

        $booking
            ->setGuest($guest)
            ->setBed($bed)
            ->setCheckIn($checkIn)
            ->setCheckOut($checkOut)
            ->setStatus($bookingData['status'] ?? 'pending')
            ->setTotalPrice((string)$bookingData['total_price']);

        $this->entityManager->persist($booking);
        $this->entityManager->flush();

        return $this->json($booking, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/bookings/{id}', name: 'app_update_booking_by_id', methods: ['PATCH'])]
    public function updateBookingById(string $id, Request $request): JsonResponse
    {
        /** @var Booking $booking */
        $booking = $this->entityManager->getRepository(Booking::class)->find($id);

        if (empty($booking)) {
            return $this->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        $bookingData = json_decode($request->getContent(), true) ?? [];

        if (isset($bookingData['guest_id'])) {
            /** @var Guest $guest */
            $guest = $this->entityManager->getRepository(Guest::class)->find($bookingData['guest_id']);

            if (empty($guest)) {
                return $this->json(['message' => 'Guest not found'], Response::HTTP_NOT_FOUND);
            }

            $booking->setGuest($guest);
        }

        if (isset($bookingData['bed_id'])) {
            /** @var Bed $bed */
            $bed = $this->entityManager->getRepository(Bed::class)->find($bookingData['bed_id']);

            if (empty($bed)) {
                return $this->json(['message' => 'Bed not found'], Response::HTTP_NOT_FOUND);
            }

            $booking->setBed($bed);
        }

        if (isset($bookingData['check_in'])) {
            $checkIn = DateTime::createFromFormat('!Y-m-d', $bookingData['check_in']);

            if (!$checkIn) {
                return $this->json(['message' => 'Dates must be in format Y-m-d'], Response::HTTP_BAD_REQUEST);
            }

            $booking->setCheckIn($checkIn);
        }

        if (isset($bookingData['check_out'])) {
            $checkOut = DateTime::createFromFormat('!Y-m-d', $bookingData['check_out']);

            if (!$checkOut) {
                return $this->json(['message' => 'Dates must be in format Y-m-d'], Response::HTTP_BAD_REQUEST);
            }

            $booking->setCheckOut($checkOut);
        }

        if (isset($bookingData['status'])) {
            $booking->setStatus($bookingData['status']);
        }

        if (isset($bookingData['total_price'])) {
            $booking->setTotalPrice((string)$bookingData['total_price']);
        }

        $this->entityManager->flush();

        return $this->json($booking, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/bookings/{id}', name: 'app_delete_booking_by_id', methods: ['DELETE'])]
    public function deleteBookingById(string $id): JsonResponse
    {
        /** @var Booking $booking */
        $booking = $this->entityManager->getRepository(Booking::class)->find($id);

        if (empty($booking)) {
            return $this->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($booking);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

}
