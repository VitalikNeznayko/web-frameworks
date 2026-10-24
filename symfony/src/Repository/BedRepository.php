<?php

namespace App\Repository;

use App\Entity\Bed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bed>
 */
class BedRepository extends ServiceEntityRepository
{
    use FilterableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bed::class);
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function createFilteredQueryBuilder(array $filters): QueryBuilder
    {
        $qb = $this->createQueryBuilder('b')
            ->join('b.room', 'r')
            ->addSelect('r')
            ->orderBy('r.number', 'ASC')
            ->addOrderBy('b.label', 'ASC')
            ->addOrderBy('b.id', 'ASC');

        $this->filterEquals($qb, 'b.id', 'id', $filters['id'] ?? null);
        $this->filterEquals($qb, 'b.room', 'room', $filters['room'] ?? null);
        $this->filterContains($qb, 'b.label', 'label', $filters['label'] ?? null);
        $this->filterEquals($qb, 'b.bedType', 'bedType', $filters['bedType'] ?? null);

        $isActive = $filters['isActive'] ?? null;
        if ('1' === $isActive || '0' === $isActive) {
            $qb->andWhere('b.isActive = :isActive')->setParameter('isActive', '1' === $isActive);
        }

        return $qb;
    }
}
