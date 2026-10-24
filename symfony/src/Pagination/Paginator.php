<?php

namespace App\Pagination;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\HttpFoundation\Request;

final class Paginator
{
    public const ITEMS_PER_PAGE_CHOICES = [5, 10, 20, 50];
    public const DEFAULT_ITEMS_PER_PAGE = 10;

    /**
     * @return PaginatedResult<mixed>
     */
    public function paginate(QueryBuilder $queryBuilder, Request $request, bool $fetchJoinCollection = false): PaginatedResult
    {
        $query = $request->query->all();
        $itemsPerPage = $this->toPositiveInt($query['itemsPerPage'] ?? null);
        if (!\in_array($itemsPerPage, self::ITEMS_PER_PAGE_CHOICES, true)) {
            $itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE;
        }
        $page = $this->toPositiveInt($query['page'] ?? null) ?? 1;

        $paginator = new DoctrinePaginator($queryBuilder->getQuery(), $fetchJoinCollection);
        $totalItems = \count($paginator);
        $totalPages = max(1, (int) ceil($totalItems / $itemsPerPage));
        $page = min($page, $totalPages);

        $paginator->getQuery()
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage);

        return new PaginatedResult(iterator_to_array($paginator, false), $page, $itemsPerPage, $totalItems);
    }

    private function toPositiveInt(mixed $value): ?int
    {
        if (!\is_scalar($value)) {
            return null;
        }

        $int = filter_var($value, \FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        return false === $int ? null : $int;
    }
}
