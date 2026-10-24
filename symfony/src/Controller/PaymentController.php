<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\Filter\PaymentFilterType;
use App\Form\PaymentType;
use App\Pagination\Paginator;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/payments')]
#[IsGranted('ROLE_MANAGER')]
final class PaymentController extends AbstractController
{
    #[Route(name: 'payment_index', methods: ['GET'])]
    public function index(Request $request, PaymentRepository $paymentRepository, Paginator $paginator): Response
    {
        $filterForm = $this->createForm(PaymentFilterType::class);
        $filterForm->handleRequest($request);

        $pagination = $paginator->paginate(
            $paymentRepository->createFilteredQueryBuilder($filterForm->getData() ?? []),
            $request,
            false,
        );

        return $this->render('payment/index.html.twig', [
            'payments' => $pagination->getItems(),
            'pagination' => $pagination,
            'filter_form' => $filterForm->createView(),
        ]);
    }

    #[Route('/new', name: 'payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($payment);
            $entityManager->flush();

            $this->addFlash('success', 'Платіж створено.');

            return $this->redirectToRoute('payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'payment_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Payment $payment): Response
    {
        return $this->render('payment/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    #[Route('/{id}/edit', name: 'payment_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Платіж оновлено.');

            return $this->redirectToRoute('payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payment/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'payment_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($payment);
            $entityManager->flush();

            $this->addFlash('success', 'Платіж видалено.');
        } else {
            $this->addFlash('error', 'Недійсний CSRF-токен.');
        }

        return $this->redirectToRoute('payment_index', [], Response::HTTP_SEE_OTHER);
    }
}
