<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @template T of object
 * @extends ServiceEntityRepository<T>
 */
abstract class AbstractFilterRepository extends ServiceEntityRepository
{

    public const FIELDS = [];

    public const DEFAULT_PAGE = 1;
    public const DEFAULT_ITEMS_PER_PAGE = 10;

    /**
     * @param array $criteria
     * @return mixed
     */
    public function getFilteredItems(array $criteria = []): mixed
    {
        $queryBuilder = $this->createQueryBuilder('items');

        $queryBuilder = $this->filterData($queryBuilder, $criteria);

        $page = max(1, (int)($criteria['page'] ?? self::DEFAULT_PAGE));
        $itemsPerPage = max(1, (int)($criteria['itemsPerPage'] ?? self::DEFAULT_ITEMS_PER_PAGE));

        return $queryBuilder
            ->orderBy('items.id', 'ASC')
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $criteria
     * @return QueryBuilder
     */
    public function filterData(QueryBuilder $queryBuilder, array $criteria = []): QueryBuilder
    {
        $alias = $queryBuilder->getRootAliases()[0];

        foreach (static::FIELDS as $key => [$field, $type]) {
            if (!isset($criteria[$key]) || $criteria[$key] === '') {
                continue;
            }

            $value = $criteria[$key];
            $column = $alias . '.' . $field;

            if ($type === 'LIKE' && !is_array($value)) {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->like($column, ':' . $key))
                    ->setParameter($key, '%' . $value . '%');
            }

            if ($type === 'EQUAL' && !is_array($value)) {
                $queryBuilder
                    ->andWhere($column . ' = :' . $key)
                    ->setParameter($key, $value);
            }

            if ($type === 'BOOL') {
                $queryBuilder
                    ->andWhere($column . ' = :' . $key)
                    ->setParameter($key, filter_var($value, FILTER_VALIDATE_BOOLEAN));
            }

            if ($type === 'BETWEEN' && !is_array($value)) {
                $queryBuilder
                    ->andWhere($column . ' = :' . $key)
                    ->setParameter($key, $value);
            }

            if ($type === 'BETWEEN' && is_array($value)) {
                if (isset($value['gte']) && $value['gte'] !== '') {
                    $queryBuilder
                        ->andWhere($column . ' >= :' . $key . 'Gte')
                        ->setParameter($key . 'Gte', $value['gte']);
                }

                if (isset($value['lte']) && $value['lte'] !== '') {
                    $queryBuilder
                        ->andWhere($column . ' <= :' . $key . 'Lte')
                        ->setParameter($key . 'Lte', $value['lte']);
                }
            }
        }

        return $queryBuilder;
    }

}
