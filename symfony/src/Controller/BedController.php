<?php

namespace App\Controller;

use App\Entity\Bed;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
final class BedController extends AbstractController
{

    public const REQUIRED_FIELDS = ['room_id', 'label', 'bed_type'];

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
    #[Route('/beds', name: 'app_get_beds', methods: ['GET'])]
    #[IsGranted(User::ROLE_CLIENT)]
    public function getBeds(Request $request): JsonResponse
    {
        $queryParams = $request->query->all();

        $beds = $this->entityManager->getRepository(Bed::class)->getFilteredItems($queryParams);

        return $this->json($beds, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/beds/{id}', name: 'app_get_bed_by_id', methods: ['GET'])]
    #[IsGranted(User::ROLE_CLIENT)]
    public function getBedById(string $id): JsonResponse
    {
        /** @var Bed $bed */
        $bed = $this->entityManager->getRepository(Bed::class)->find($id);

        if (empty($bed)) {
            return $this->json(['message' => 'Bed not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($bed, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/beds', name: 'app_create_bed', methods: ['POST'])]
    #[IsGranted(User::ROLE_MANAGER)]
    public function createBed(Request $request): JsonResponse
    {
        $bedData = json_decode($request->getContent(), true) ?? [];

        $missingFields = array_diff(self::REQUIRED_FIELDS, array_keys($bedData));

        if (!empty($missingFields)) {
            return $this->json(
                ['message' => 'Missing required fields: ' . implode(', ', $missingFields)],
                Response::HTTP_BAD_REQUEST
            );
        }

        /** @var Room $room */
        $room = $this->entityManager->getRepository(Room::class)->find($bedData['room_id']);

        if (empty($room)) {
            return $this->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }

        $bed = new Bed();

        $bed
            ->setRoom($room)
            ->setLabel($bedData['label'])
            ->setBedType($bedData['bed_type'])
            ->setIsActive((bool)($bedData['is_active'] ?? true));

        $this->entityManager->persist($bed);
        $this->entityManager->flush();

        return $this->json($bed, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/beds/{id}', name: 'app_update_bed_by_id', methods: ['PATCH'])]
    #[IsGranted(User::ROLE_MANAGER)]
    public function updateBedById(string $id, Request $request): JsonResponse
    {
        /** @var Bed $bed */
        $bed = $this->entityManager->getRepository(Bed::class)->find($id);

        if (empty($bed)) {
            return $this->json(['message' => 'Bed not found'], Response::HTTP_NOT_FOUND);
        }

        $bedData = json_decode($request->getContent(), true) ?? [];

        if (isset($bedData['room_id'])) {
            /** @var Room $room */
            $room = $this->entityManager->getRepository(Room::class)->find($bedData['room_id']);

            if (empty($room)) {
                return $this->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
            }

            $bed->setRoom($room);
        }

        if (isset($bedData['label'])) {
            $bed->setLabel($bedData['label']);
        }

        if (isset($bedData['bed_type'])) {
            $bed->setBedType($bedData['bed_type']);
        }

        if (isset($bedData['is_active'])) {
            $bed->setIsActive((bool)$bedData['is_active']);
        }

        $this->entityManager->flush();

        return $this->json($bed, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/beds/{id}', name: 'app_delete_bed_by_id', methods: ['DELETE'])]
    #[IsGranted(User::ROLE_ADMIN)]
    public function deleteBedById(string $id): JsonResponse
    {
        /** @var Bed $bed */
        $bed = $this->entityManager->getRepository(Bed::class)->find($id);

        if (empty($bed)) {
            return $this->json(['message' => 'Bed not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($bed);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

}
