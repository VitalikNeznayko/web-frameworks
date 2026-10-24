<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\Filter\RoomFilterType;
use App\Form\RoomType;
use App\Pagination\Paginator;
use App\Repository\RoomRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rooms')]
final class RoomController extends AbstractController
{
    #[Route(name: 'room_index', methods: ['GET'])]
    public function index(Request $request, RoomRepository $roomRepository, Paginator $paginator): Response
    {
        $filterForm = $this->createForm(RoomFilterType::class);
        $filterForm->handleRequest($request);

        $pagination = $paginator->paginate(
            $roomRepository->createFilteredQueryBuilder($filterForm->getData() ?? []),
            $request,
            true,
        );

        return $this->render('room/index.html.twig', [
            'rooms' => $pagination->getItems(),
            'pagination' => $pagination,
            'filter_form' => $filterForm->createView(),
        ]);
    }

    #[Route('/new', name: 'room_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($room);
            $entityManager->flush();

            $this->addFlash('success', 'Кімнату створено.');

            return $this->redirectToRoute('room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('room/new.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'room_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }

    #[Route('/{id}/edit', name: 'room_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Room $room, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Кімнату оновлено.');

            return $this->redirectToRoute('room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'room_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Room $room, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$room->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('error', 'Недійсний CSRF-токен.');

            return $this->redirectToRoute('room_index', [], Response::HTTP_SEE_OTHER);
        }

        foreach ($room->getBeds() as $bed) {
            if (!$bed->getBookings()->isEmpty()) {
                $this->addFlash('error', 'Неможливо видалити кімнату: на її ліжка є бронювання.');

                return $this->redirectToRoute('room_show', ['id' => $room->getId()], Response::HTTP_SEE_OTHER);
            }
        }

        try {
            $entityManager->remove($room);
            $entityManager->flush();
            $this->addFlash('success', 'Кімнату видалено.');
        } catch (ForeignKeyConstraintViolationException) {
            $this->addFlash('error', 'Неможливо видалити кімнату: існують пов\'язані записи.');
        }

        return $this->redirectToRoute('room_index', [], Response::HTTP_SEE_OTHER);
    }
}
