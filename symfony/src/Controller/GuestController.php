<?php

namespace App\Controller;

use App\Entity\Guest;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class GuestController extends AbstractController
{

    public const REQUIRED_FIELDS = ['first_name', 'last_name', 'email', 'phone', 'passport_number', 'country'];

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
    #[Route('/guests', name: 'app_get_guests', methods: ['GET'])]
    public function getGuests(): JsonResponse
    {
        $guests = $this->entityManager->getRepository(Guest::class)->findAll();

        return $this->json($guests, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/guests/{id}', name: 'app_get_guest_by_id', methods: ['GET'])]
    public function getGuestById(string $id): JsonResponse
    {
        /** @var Guest $guest */
        $guest = $this->entityManager->getRepository(Guest::class)->find($id);

        if (empty($guest)) {
            return $this->json(['message' => 'Guest not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($guest, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/guests', name: 'app_create_guest', methods: ['POST'])]
    public function createGuest(Request $request): JsonResponse
    {
        $guestData = json_decode($request->getContent(), true) ?? [];

        $missingFields = array_diff(self::REQUIRED_FIELDS, array_keys($guestData));

        if (!empty($missingFields)) {
            return $this->json(
                ['message' => 'Missing required fields: ' . implode(', ', $missingFields)],
                Response::HTTP_BAD_REQUEST
            );
        }

        $guest = new Guest();

        $guest
            ->setFirstName($guestData['first_name'])
            ->setLastName($guestData['last_name'])
            ->setEmail($guestData['email'])
            ->setPhone($guestData['phone'])
            ->setPassportNumber($guestData['passport_number'])
            ->setCountry($guestData['country']);

        try {
            $this->entityManager->persist($guest);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException) {
            return $this->json(['message' => 'Guest with this email already exists'], Response::HTTP_CONFLICT);
        }

        return $this->json($guest, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/guests/{id}', name: 'app_update_guest_by_id', methods: ['PATCH'])]
    public function updateGuestById(string $id, Request $request): JsonResponse
    {
        /** @var Guest $guest */
        $guest = $this->entityManager->getRepository(Guest::class)->find($id);

        if (empty($guest)) {
            return $this->json(['message' => 'Guest not found'], Response::HTTP_NOT_FOUND);
        }

        $guestData = json_decode($request->getContent(), true) ?? [];

        if (isset($guestData['first_name'])) {
            $guest->setFirstName($guestData['first_name']);
        }

        if (isset($guestData['last_name'])) {
            $guest->setLastName($guestData['last_name']);
        }

        if (isset($guestData['email'])) {
            $guest->setEmail($guestData['email']);
        }

        if (isset($guestData['phone'])) {
            $guest->setPhone($guestData['phone']);
        }

        if (isset($guestData['passport_number'])) {
            $guest->setPassportNumber($guestData['passport_number']);
        }

        if (isset($guestData['country'])) {
            $guest->setCountry($guestData['country']);
        }

        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException) {
            return $this->json(['message' => 'Guest with this email already exists'], Response::HTTP_CONFLICT);
        }

        return $this->json($guest, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/guests/{id}', name: 'app_delete_guest_by_id', methods: ['DELETE'])]
    public function deleteGuestById(string $id): JsonResponse
    {
        /** @var Guest $guest */
        $guest = $this->entityManager->getRepository(Guest::class)->find($id);

        if (empty($guest)) {
            return $this->json(['message' => 'Guest not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($guest);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

}
