<?php

namespace App\Controller;

use App\Entity\Room;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class RoomController extends AbstractController
{

    public const REQUIRED_FIELDS = ['number', 'type', 'floor', 'price_per_night'];

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
    #[Route('/rooms', name: 'app_get_rooms', methods: ['GET'])]
    public function getRooms(): JsonResponse
    {
        $rooms = $this->entityManager->getRepository(Room::class)->findAll();

        return $this->json($rooms, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/rooms/{id}', name: 'app_get_room_by_id', methods: ['GET'])]
    public function getRoomById(string $id): JsonResponse
    {
        /** @var Room $room */
        $room = $this->entityManager->getRepository(Room::class)->find($id);

        if (empty($room)) {
            return $this->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($room, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/rooms', name: 'app_create_room', methods: ['POST'])]
    public function createRoom(Request $request): JsonResponse
    {
        $roomData = json_decode($request->getContent(), true) ?? [];

        $missingFields = array_diff(self::REQUIRED_FIELDS, array_keys($roomData));

        if (!empty($missingFields)) {
            return $this->json(
                ['message' => 'Missing required fields: ' . implode(', ', $missingFields)],
                Response::HTTP_BAD_REQUEST
            );
        }

        $room = new Room();

        $room
            ->setNumber($roomData['number'])
            ->setType($roomData['type'])
            ->setFloor((int)$roomData['floor'])
            ->setPricePerNight((string)$roomData['price_per_night'])
            ->setDescription($roomData['description'] ?? null);

        try {
            $this->entityManager->persist($room);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException) {
            return $this->json(['message' => 'Room with this number already exists'], Response::HTTP_CONFLICT);
        }

        return $this->json($room, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/rooms/{id}', name: 'app_update_room_by_id', methods: ['PATCH'])]
    public function updateRoomById(string $id, Request $request): JsonResponse
    {
        /** @var Room $room */
        $room = $this->entityManager->getRepository(Room::class)->find($id);

        if (empty($room)) {
            return $this->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }

        $roomData = json_decode($request->getContent(), true) ?? [];

        if (isset($roomData['number'])) {
            $room->setNumber($roomData['number']);
        }

        if (isset($roomData['type'])) {
            $room->setType($roomData['type']);
        }

        if (isset($roomData['floor'])) {
            $room->setFloor((int)$roomData['floor']);
        }

        if (isset($roomData['price_per_night'])) {
            $room->setPricePerNight((string)$roomData['price_per_night']);
        }

        if (array_key_exists('description', $roomData)) {
            $room->setDescription($roomData['description']);
        }

        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException) {
            return $this->json(['message' => 'Room with this number already exists'], Response::HTTP_CONFLICT);
        }

        return $this->json($room, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/rooms/{id}', name: 'app_delete_room_by_id', methods: ['DELETE'])]
    public function deleteRoomById(string $id): JsonResponse
    {
        /** @var Room $room */
        $room = $this->entityManager->getRepository(Room::class)->find($id);

        if (empty($room)) {
            return $this->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($room);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

}
