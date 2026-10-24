<?php

namespace App\Controller;

use App\Repository\AmenityRepository;
use App\Repository\BedRepository;
use App\Repository\BookingRepository;
use App\Repository\GuestRepository;
use App\Repository\PaymentRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
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
        UserRepository $userRepository,
    ): Response {
        $sections = [
            ['title' => 'Кімнати', 'route' => 'room_index', 'new' => 'room_new', 'role' => 'ROLE_CLIENT', 'new_role' => 'ROLE_MANAGER', 'repository' => $roomRepository],
            ['title' => 'Зручності', 'route' => 'amenity_index', 'new' => 'amenity_new', 'role' => 'ROLE_CLIENT', 'new_role' => 'ROLE_MANAGER', 'repository' => $amenityRepository],
            ['title' => 'Ліжка', 'route' => 'bed_index', 'new' => 'bed_new', 'role' => 'ROLE_CLIENT', 'new_role' => 'ROLE_MANAGER', 'repository' => $bedRepository],
            ['title' => 'Гості', 'route' => 'guest_index', 'new' => 'guest_new', 'role' => 'ROLE_MANAGER', 'new_role' => 'ROLE_MANAGER', 'repository' => $guestRepository],
            ['title' => 'Бронювання', 'route' => 'booking_index', 'new' => 'booking_new', 'role' => 'ROLE_MANAGER', 'new_role' => 'ROLE_MANAGER', 'repository' => $bookingRepository],
            ['title' => 'Платежі', 'route' => 'payment_index', 'new' => 'payment_new', 'role' => 'ROLE_MANAGER', 'new_role' => 'ROLE_MANAGER', 'repository' => $paymentRepository],
            ['title' => 'Користувачі', 'route' => 'user_index', 'new' => 'user_new', 'role' => 'ROLE_ADMIN', 'new_role' => 'ROLE_ADMIN', 'repository' => $userRepository],
        ];

        $sections = array_values(array_filter($sections, fn (array $section): bool => $this->isGranted($section['role'])));

        return $this->render('home/index.html.twig', [
            'sections' => array_map(fn (array $section): array => [
                'title' => $section['title'],
                'route' => $section['route'],
                'new' => $this->isGranted($section['new_role']) ? $section['new'] : null,
                'count' => $section['repository']->count([]),
            ], $sections),
        ]);
    }
}
