<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    use FilterableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function createFilteredQueryBuilder(array $filters): QueryBuilder
    {
        $qb = $this->createQueryBuilder('bk')
            ->join('bk.guest', 'g')
            ->join('bk.bed', 'b')
            ->join('b.room', 'r')
            ->addSelect('g', 'b', 'r')
            ->orderBy('bk.checkIn', 'DESC')
            ->addOrderBy('bk.id', 'DESC');

        $this->filterEquals($qb, 'bk.id', 'id', $filters['id'] ?? null);
        $this->filterEquals($qb, 'bk.guest', 'guest', $filters['guest'] ?? null);
        $this->filterEquals($qb, 'bk.bed', 'bed', $filters['bed'] ?? null);
        $this->filterEquals($qb, 'b.room', 'room', $filters['room'] ?? null);
        $this->filterDateRange($qb, 'bk.checkIn', 'checkIn', $filters['checkInFrom'] ?? null, $filters['checkInTo'] ?? null);
        $this->filterDateRange($qb, 'bk.checkOut', 'checkOut', $filters['checkOutFrom'] ?? null, $filters['checkOutTo'] ?? null);
        $this->filterEquals($qb, 'bk.status', 'status', $filters['status'] ?? null);
        $this->filterRange($qb, 'bk.totalPrice', 'totalPrice', $filters['totalPriceMin'] ?? null, $filters['totalPriceMax'] ?? null);
        $this->filterContains($qb, 'bk.notes', 'notes', $filters['notes'] ?? null);
        $this->filterDateRange($qb, 'bk.createdAt', 'createdAt', $filters['createdAtFrom'] ?? null, $filters['createdAtTo'] ?? null);

        return $qb;
    }
}
