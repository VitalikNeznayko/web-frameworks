<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Security\JwtCookieManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class SecurityController extends AbstractController
{
    public function __construct(
        private readonly JwtCookieManager $cookieManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, UserRepository $userRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $email = trim($request->request->getString('email'));
        $error = null;

        if ($request->isMethod('POST')) {
            $user = '' === $email ? null : $userRepository->findOneByEmail($email);

            if (!$this->isCsrfTokenValid('authenticate', $request->request->getString('_csrf_token'))) {
                $error = 'Недійсний CSRF-токен. Спробуйте ще раз.';
            } elseif (null === $user || !$this->passwordHasher->isPasswordValid($user, $request->request->getString('password'))) {
                $error = 'Невірний email або пароль.';
            } else {
                $this->addFlash('success', \sprintf('Вітаємо, %s!', $user->getFullName()));

                return $this->cookieManager->login($this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER), $user, $request);
            }
        } elseif ($request->query->has('expired')) {
            $error = 'Сесія завершилась або токен недійсний. Увійдіть знову.';
        }

        return $this->render('security/login.html.twig', [
            'last_email' => $email,
            'error' => $error,
        ], new Response(status: null !== $error && $request->isMethod('POST') ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRole(User::ROLE_CLIENT);
            $user->setPassword($this->passwordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Реєстрацію завершено. Ласкаво просимо!');

            return $this->cookieManager->login($this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER), $user, $request);
        }

        return $this->render('security/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('logout', $request->request->getString('_csrf_token'))) {
            $this->addFlash('error', 'Недійсний CSRF-токен.');

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->cookieManager->logout($this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER));
    }
}
