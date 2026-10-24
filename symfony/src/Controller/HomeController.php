<?php

namespace App\Controller;

use App\Repository\AmenityRepository;
use App\Repository\BedRepository;
use App\Repository\BookingRepository;
use App\Repository\GuestRepository;
use App\Repository\PaymentRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(
        RoomRepository $roomRepository,
        AmenityRepository $amenityRepository,
        BedRepository $bedRepository,
        GuestRepository $guestRepository,
        BookingRepository $bookingRepository,
        PaymentRepository $paymentRepository,
    ): Response {
        $sections = [
            ['title' => 'Кімнати', 'route' => 'room_index', 'new' => 'room_new', 'count' => $roomRepository->count([])],
            ['title' => 'Зручності', 'route' => 'amenity_index', 'new' => 'amenity_new', 'count' => $amenityRepository->count([])],
            ['title' => 'Ліжка', 'route' => 'bed_index', 'new' => 'bed_new', 'count' => $bedRepository->count([])],
            ['title' => 'Гості', 'route' => 'guest_index', 'new' => 'guest_new', 'count' => $guestRepository->count([])],
            ['title' => 'Бронювання', 'route' => 'booking_index', 'new' => 'booking_new', 'count' => $bookingRepository->count([])],
            ['title' => 'Платежі', 'route' => 'payment_index', 'new' => 'payment_new', 'count' => $paymentRepository->count([])],
        ];

        return $this->render('home/index.html.twig', [
            'sections' => $sections,
        ]);
    }
}
