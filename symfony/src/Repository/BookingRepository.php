<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractFilterRepository<Booking>
 */
class BookingRepository extends AbstractFilterRepository
{

    public const FIELDS = [
        'id'          => ['id', 'EQUAL'],
        'guest_id'    => ['guest', 'EQUAL'],
        'bed_id'      => ['bed', 'EQUAL'],
        'check_in'    => ['checkIn', 'BETWEEN'],
        'check_out'   => ['checkOut', 'BETWEEN'],
        'status'      => ['status', 'LIKE'],
        'total_price' => ['totalPrice', 'BETWEEN']
    ];

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

}
