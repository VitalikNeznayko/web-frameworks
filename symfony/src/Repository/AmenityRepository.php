<?php

namespace App\Repository;

use App\Entity\Amenity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Amenity>
 */
class AmenityRepository extends ServiceEntityRepository
{
    use FilterableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Amenity::class);
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function createFilteredQueryBuilder(array $filters): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC');

        $this->filterEquals($qb, 'a.id', 'id', $filters['id'] ?? null);
        $this->filterContains($qb, 'a.name', 'name', $filters['name'] ?? null);
        $this->filterContains($qb, 'a.description', 'description', $filters['description'] ?? null);

        if (null !== ($filters['room'] ?? null)) {
            $qb->andWhere(':room MEMBER OF a.rooms')->setParameter('room', $filters['room']);
        }

        return $qb;
    }
}
