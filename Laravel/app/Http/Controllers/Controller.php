<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Controller
{
    public const PER_PAGE_OPTIONS = [5, 10, 20, 50];

    public const DEFAULT_PER_PAGE = 10;

    public static function perPage(Request $request): int
    {
        $value = $request->query('itemsPerPage');

        return is_string($value) && in_array($value, array_map('strval', self::PER_PAGE_OPTIONS), true)
            ? (int) $value
            : self::DEFAULT_PER_PAGE;
    }

    protected function paginate(Builder $query, Request $request): LengthAwarePaginator
    {
        return $query->paginate(self::perPage($request))->withQueryString();
    }
}
