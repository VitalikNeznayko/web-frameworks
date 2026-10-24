<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

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

    protected static function resourceAuthorization(string $model, string $parameter): array
    {
        return [
            new Middleware("can:viewAny,{$model}", only: ['index']),
            new Middleware("can:view,{$parameter}", only: ['show']),
            new Middleware("can:create,{$model}", only: ['create', 'store']),
            new Middleware("can:update,{$parameter}", only: ['edit', 'update']),
            new Middleware("can:delete,{$parameter}", only: ['destroy']),
        ];
    }

    protected function paginate(Builder $query, Request $request): LengthAwarePaginator
    {
        return $query->paginate(self::perPage($request))->withQueryString();
    }
}
