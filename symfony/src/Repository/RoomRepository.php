<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractFilterRepository<Room>
 */
class RoomRepository extends AbstractFilterRepository
{

    public const FIELDS = [
        'id'              => ['id', 'EQUAL'],
        'number'          => ['number', 'LIKE'],
        'type'            => ['type', 'LIKE'],
        'floor'           => ['floor', 'BETWEEN'],
        'price_per_night' => ['pricePerNight', 'BETWEEN'],
        'description'     => ['description', 'LIKE']
    ];

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

}
