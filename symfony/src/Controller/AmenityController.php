<?php

namespace App\Controller;

use App\Entity\Amenity;
use App\Form\AmenityType;
use App\Repository\AmenityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/amenities')]
final class AmenityController extends AbstractController
{
    #[Route(name: 'amenity_index', methods: ['GET'])]
    public function index(AmenityRepository $amenityRepository): Response
    {
        return $this->render('amenity/index.html.twig', [
            'amenities' => $amenityRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'amenity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $amenity = new Amenity();
        $form = $this->createForm(AmenityType::class, $amenity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($amenity);
            $entityManager->flush();

            $this->addFlash('success', 'Зручність створено.');

            return $this->redirectToRoute('amenity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('amenity/new.html.twig', [
            'amenity' => $amenity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'amenity_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Amenity $amenity): Response
    {
        return $this->render('amenity/show.html.twig', [
            'amenity' => $amenity,
        ]);
    }

    #[Route('/{id}/edit', name: 'amenity_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Amenity $amenity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AmenityType::class, $amenity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Зручність оновлено.');

            return $this->redirectToRoute('amenity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('amenity/edit.html.twig', [
            'amenity' => $amenity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'amenity_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Amenity $amenity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$amenity->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($amenity);
            $entityManager->flush();

            $this->addFlash('success', 'Зручність видалено.');
        } else {
            $this->addFlash('error', 'Недійсний CSRF-токен.');
        }

        return $this->redirectToRoute('amenity_index', [], Response::HTTP_SEE_OTHER);
    }
}
