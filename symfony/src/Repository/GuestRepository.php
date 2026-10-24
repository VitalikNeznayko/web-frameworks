<?php

namespace App\Repository;

use App\Entity\Guest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Guest>
 */
class GuestRepository extends ServiceEntityRepository
{
    use FilterableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guest::class);
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function createFilteredQueryBuilder(array $filters): QueryBuilder
    {
        $qb = $this->createQueryBuilder('g')
            ->orderBy('g.lastName', 'ASC')
            ->addOrderBy('g.firstName', 'ASC')
            ->addOrderBy('g.id', 'ASC');

        $this->filterEquals($qb, 'g.id', 'id', $filters['id'] ?? null);

        foreach (['firstName', 'lastName', 'email', 'phone', 'passportNumber', 'country'] as $field) {
            $this->filterContains($qb, 'g.'.$field, $field, $filters[$field] ?? null);
        }

        $this->filterDateRange($qb, 'g.birthDate', 'birthDate', $filters['birthDateFrom'] ?? null, $filters['birthDateTo'] ?? null);

        return $qb;
    }
}
