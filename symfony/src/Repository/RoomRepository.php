<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    use FilterableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function createFilteredQueryBuilder(array $filters): QueryBuilder
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.amenities', 'a')
            ->addSelect('a')
            ->orderBy('r.number', 'ASC');

        $this->filterEquals($qb, 'r.id', 'id', $filters['id'] ?? null);
        $this->filterContains($qb, 'r.number', 'number', $filters['number'] ?? null);
        $this->filterEquals($qb, 'r.type', 'type', $filters['type'] ?? null);
        $this->filterRange($qb, 'r.floor', 'floor', $filters['floorMin'] ?? null, $filters['floorMax'] ?? null);
        $this->filterRange($qb, 'r.pricePerNight', 'price', $filters['pricePerNightMin'] ?? null, $filters['pricePerNightMax'] ?? null);
        $this->filterContains($qb, 'r.description', 'description', $filters['description'] ?? null);

        if (null !== ($filters['amenity'] ?? null)) {
            $qb->andWhere(':amenity MEMBER OF r.amenities')->setParameter('amenity', $filters['amenity']);
        }

        return $qb;
    }
}
