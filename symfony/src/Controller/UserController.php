<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Filter\UserFilterType;
use App\Form\UserType;
use App\Pagination\Paginator;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/users')]
#[IsGranted('ROLE_ADMIN')]
final class UserController extends AbstractController
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    #[Route(name: 'user_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository, Paginator $paginator): Response
    {
        $filterForm = $this->createForm(UserFilterType::class);
        $filterForm->handleRequest($request);

        $pagination = $paginator->paginate(
            $userRepository->createFilteredQueryBuilder($filterForm->getData() ?? []),
            $request,
        );

        return $this->render('user/index.html.twig', [
            'users' => $pagination->getItems(),
            'pagination' => $pagination,
            'filter_form' => $filterForm->createView(),
        ]);
    }

    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->applyPassword($user, $form);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Користувача створено.');

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $isSelf = $this->isCurrentUser($user);
        $form = $this->createForm(UserType::class, $user, [
            'require_password' => false,
            'lock_role' => $isSelf,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($isSelf) {
                $user->setRole(User::ROLE_ADMIN);
            }
            $this->applyPassword($user, $form);
            $entityManager->flush();

            $this->addFlash('success', 'Користувача оновлено.');

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCurrentUser($user)) {
            $this->addFlash('error', 'Ви не можете видалити власний обліковий запис.');

            return $this->redirectToRoute('user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        if (!$this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('error', 'Недійсний CSRF-токен.');

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'Користувача видалено.');

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }

    private function isCurrentUser(User $user): bool
    {
        $current = $this->getUser();

        return $current instanceof User && $current->getId() === $user->getId();
    }

    private function applyPassword(User $user, FormInterface $form): void
    {
        $plainPassword = $form->get('plainPassword')->getData();
        if (\is_string($plainPassword) && '' !== $plainPassword) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
        }
    }
}
