<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Form\GuestType;
use App\Repository\GuestRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/guests')]
final class GuestController extends AbstractController
{
    #[Route(name: 'guest_index', methods: ['GET'])]
    public function index(GuestRepository $guestRepository): Response
    {
        return $this->render('guest/index.html.twig', [
            'guests' => $guestRepository->findBy([], ['lastName' => 'ASC', 'firstName' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'guest_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $guest = new Guest();
        $form = $this->createForm(GuestType::class, $guest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($guest);
            $entityManager->flush();

            $this->addFlash('success', 'Гостя створено.');

            return $this->redirectToRoute('guest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guest/new.html.twig', [
            'guest' => $guest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'guest_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Guest $guest): Response
    {
        return $this->render('guest/show.html.twig', [
            'guest' => $guest,
        ]);
    }

    #[Route('/{id}/edit', name: 'guest_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Guest $guest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GuestType::class, $guest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Дані гостя оновлено.');

            return $this->redirectToRoute('guest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guest/edit.html.twig', [
            'guest' => $guest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'guest_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Guest $guest, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$guest->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('error', 'Недійсний CSRF-токен.');

            return $this->redirectToRoute('guest_index', [], Response::HTTP_SEE_OTHER);
        }

        if (!$guest->getBookings()->isEmpty()) {
            $this->addFlash('error', 'Неможливо видалити гостя: у нього є бронювання.');

            return $this->redirectToRoute('guest_show', ['id' => $guest->getId()], Response::HTTP_SEE_OTHER);
        }

        try {
            $entityManager->remove($guest);
            $entityManager->flush();
            $this->addFlash('success', 'Гостя видалено.');
        } catch (ForeignKeyConstraintViolationException) {
            $this->addFlash('error', 'Неможливо видалити гостя: у нього є бронювання.');
        }

        return $this->redirectToRoute('guest_index', [], Response::HTTP_SEE_OTHER);
    }
}
