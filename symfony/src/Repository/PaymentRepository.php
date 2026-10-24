<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 */
class PaymentRepository extends ServiceEntityRepository
{
    use FilterableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function createFilteredQueryBuilder(array $filters): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.booking', 'bk')
            ->join('bk.guest', 'g')
            ->join('bk.bed', 'b')
            ->join('b.room', 'r')
            ->addSelect('bk', 'g', 'b', 'r')
            ->orderBy('p.id', 'DESC');

        $this->filterEquals($qb, 'p.id', 'id', $filters['id'] ?? null);
        $this->filterEquals($qb, 'p.booking', 'booking', $filters['booking'] ?? null);
        $this->filterRange($qb, 'p.amount', 'amount', $filters['amountMin'] ?? null, $filters['amountMax'] ?? null);
        $this->filterEquals($qb, 'p.method', 'method', $filters['method'] ?? null);
        $this->filterEquals($qb, 'p.status', 'status', $filters['status'] ?? null);
        $this->filterDateRange($qb, 'p.paidAt', 'paidAt', $filters['paidAtFrom'] ?? null, $filters['paidAtTo'] ?? null);

        return $qb;
    }
}
