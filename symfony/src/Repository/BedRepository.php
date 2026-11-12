<?php

namespace App\Repository;

use App\Entity\Bed;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractFilterRepository<Bed>
 */
class BedRepository extends AbstractFilterRepository
{

    public const FIELDS = [
        'id'        => ['id', 'EQUAL'],
        'room_id'   => ['room', 'EQUAL'],
        'label'     => ['label', 'LIKE'],
        'bed_type'  => ['bedType', 'LIKE'],
        'is_active' => ['isActive', 'BOOL']
    ];

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bed::class);
    }

}
