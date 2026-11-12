<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class AuthController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $userData = json_decode($request->getContent(), true) ?? [];

        if (empty($userData['email']) || empty($userData['password'])) {
            return $this->json(['message' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json(['message' => 'Invalid email'], Response::HTTP_BAD_REQUEST);
        }

        if (strlen($userData['password']) < 6) {
            return $this->json(['message' => 'Password must be at least 6 characters'], Response::HTTP_BAD_REQUEST);
        }

        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $userData['email']]);

        if (!empty($existingUser)) {
            return $this->json(['message' => 'User with this email already exists'], Response::HTTP_CONFLICT);
        }

        $user = new User();

        $user
            ->setEmail($userData['email'])
            ->setRoles([User::ROLE_CLIENT])
            ->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json($user, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('/me', name: 'app_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        return $this->json($this->getUser(), Response::HTTP_OK);
    }

}
