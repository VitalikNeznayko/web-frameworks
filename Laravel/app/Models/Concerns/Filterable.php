<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait Filterable
{

    /**
     * @param array $criteria
     * @return Collection
     */
    public static function getFilteredItems(array $criteria = []): Collection
    {
        $query = static::query();

        static::filterData($query, $criteria);

        $page = max(1, (int)($criteria['page'] ?? 1));
        $itemsPerPage = max(1, (int)($criteria['itemsPerPage'] ?? 10));

        return $query
            ->orderBy('id')
            ->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();
    }

    /**
     * @param Builder $query
     * @param array $criteria
     * @return Builder
     */
    public static function filterData(Builder $query, array $criteria = []): Builder
    {
        foreach (static::FILTER_FIELDS as $field => $type) {
            if (!isset($criteria[$field]) || $criteria[$field] === '') {
                continue;
            }

            $value = $criteria[$field];

            if ($type === 'LIKE' && !is_array($value)) {
                $query->where($field, 'LIKE', '%' . $value . '%');
            }

            if ($type === 'EQUAL' && !is_array($value)) {
                $query->where($field, $value);
            }

            if ($type === 'BOOL') {
                $query->where($field, filter_var($value, FILTER_VALIDATE_BOOLEAN));
            }

            if ($type === 'BETWEEN' && !is_array($value)) {
                $query->where($field, $value);
            }

            if ($type === 'BETWEEN' && is_array($value)) {
                if (isset($value['gte']) && $value['gte'] !== '') {
                    $query->where($field, '>=', $value['gte']);
                }

                if (isset($value['lte']) && $value['lte'] !== '') {
                    $query->where($field, '<=', $value['lte']);
                }
            }
        }

        return $query;
    }

}
