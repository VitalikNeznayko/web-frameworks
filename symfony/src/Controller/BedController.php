<?php

namespace App\Controller;

use App\Entity\Bed;
use App\Form\BedType;
use App\Repository\BedRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/beds')]
final class BedController extends AbstractController
{
    #[Route(name: 'bed_index', methods: ['GET'])]
    public function index(BedRepository $bedRepository): Response
    {
        $beds = $bedRepository->createQueryBuilder('b')
            ->join('b.room', 'r')
            ->addSelect('r')
            ->orderBy('r.number', 'ASC')
            ->addOrderBy('b.label', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('bed/index.html.twig', [
            'beds' => $beds,
        ]);
    }

    #[Route('/new', name: 'bed_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bed = new Bed();
        $form = $this->createForm(BedType::class, $bed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bed);
            $entityManager->flush();

            $this->addFlash('success', 'Ліжко створено.');

            return $this->redirectToRoute('bed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bed/new.html.twig', [
            'bed' => $bed,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'bed_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Bed $bed): Response
    {
        return $this->render('bed/show.html.twig', [
            'bed' => $bed,
        ]);
    }

    #[Route('/{id}/edit', name: 'bed_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Bed $bed, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BedType::class, $bed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Ліжко оновлено.');

            return $this->redirectToRoute('bed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bed/edit.html.twig', [
            'bed' => $bed,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'bed_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Bed $bed, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$bed->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('error', 'Недійсний CSRF-токен.');

            return $this->redirectToRoute('bed_index', [], Response::HTTP_SEE_OTHER);
        }

        if (!$bed->getBookings()->isEmpty()) {
            $this->addFlash('error', 'Неможливо видалити ліжко: для нього існують бронювання.');

            return $this->redirectToRoute('bed_show', ['id' => $bed->getId()], Response::HTTP_SEE_OTHER);
        }

        try {
            $entityManager->remove($bed);
            $entityManager->flush();
            $this->addFlash('success', 'Ліжко видалено.');
        } catch (ForeignKeyConstraintViolationException) {
            $this->addFlash('error', 'Неможливо видалити ліжко: для нього існують бронювання.');
        }

        return $this->redirectToRoute('bed_index', [], Response::HTTP_SEE_OTHER);
    }
}
