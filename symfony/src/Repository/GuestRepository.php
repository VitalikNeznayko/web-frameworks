<?php

namespace App\Repository;

use App\Entity\Guest;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractFilterRepository<Guest>
 */
class GuestRepository extends AbstractFilterRepository
{

    public const FIELDS = [
        'id'              => ['id', 'EQUAL'],
        'first_name'      => ['firstName', 'LIKE'],
        'last_name'       => ['lastName', 'LIKE'],
        'email'           => ['email', 'LIKE'],
        'phone'           => ['phone', 'LIKE'],
        'passport_number' => ['passportNumber', 'LIKE'],
        'country'         => ['country', 'LIKE']
    ];

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guest::class);
    }

}
