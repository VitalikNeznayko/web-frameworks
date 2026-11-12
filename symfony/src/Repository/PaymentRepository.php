<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractFilterRepository<Payment>
 */
class PaymentRepository extends AbstractFilterRepository
{

    public const FIELDS = [
        'id'         => ['id', 'EQUAL'],
        'booking_id' => ['booking', 'EQUAL'],
        'amount'     => ['amount', 'BETWEEN'],
        'method'     => ['method', 'LIKE'],
        'status'     => ['status', 'LIKE'],
        'paid_at'    => ['paidAt', 'BETWEEN']
    ];

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

}
