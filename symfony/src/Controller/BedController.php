<?php

namespace App\Controller;

use App\Entity\Bed;
use App\Form\Filter\BedFilterType;
use App\Form\BedType;
use App\Pagination\Paginator;
use App\Repository\BedRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/beds')]
#[IsGranted('ROLE_CLIENT')]
final class BedController extends AbstractController
{
    #[Route(name: 'bed_index', methods: ['GET'])]
    public function index(Request $request, BedRepository $bedRepository, Paginator $paginator): Response
    {
        $filterForm = $this->createForm(BedFilterType::class);
        $filterForm->handleRequest($request);

        $pagination = $paginator->paginate(
            $bedRepository->createFilteredQueryBuilder($filterForm->getData() ?? []),
            $request,
            false,
        );

        return $this->render('bed/index.html.twig', [
            'beds' => $pagination->getItems(),
            'pagination' => $pagination,
            'filter_form' => $filterForm->createView(),
        ]);
    }

    #[Route('/new', name: 'bed_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_MANAGER')]
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
    #[IsGranted('ROLE_MANAGER')]
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
    #[IsGranted('ROLE_ADMIN')]
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
