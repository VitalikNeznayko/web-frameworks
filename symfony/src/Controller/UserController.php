<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
#[IsGranted(User::ROLE_ADMIN)]
final class UserController extends AbstractController
{

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
    #[Route('/users', name: 'app_get_users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return $this->json($users, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/users/{id}/role', name: 'app_update_user_role', methods: ['PATCH'])]
    public function updateUserRole(string $id, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (empty($user)) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $userData = json_decode($request->getContent(), true) ?? [];

        if (empty($userData['role']) || !in_array($userData['role'], User::ROLES, true)) {
            return $this->json(
                ['message' => 'Role must be one of: ' . implode(', ', User::ROLES)],
                Response::HTTP_BAD_REQUEST
            );
        }

        $user->setRoles([$userData['role']]);

        $this->entityManager->flush();

        return $this->json($user, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/users/{id}', name: 'app_delete_user_by_id', methods: ['DELETE'])]
    public function deleteUserById(string $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (empty($user)) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        if ($user === $this->getUser()) {
            return $this->json(['message' => 'You cannot delete yourself'], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

}
