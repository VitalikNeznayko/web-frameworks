<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

trait FilterableRepositoryTrait
{
    private function filterEquals(QueryBuilder $qb, string $field, string $param, mixed $value): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        $qb->andWhere(\sprintf('%s = :%s', $field, $param))->setParameter($param, $value);
    }

    private function filterContains(QueryBuilder $qb, string $field, string $param, mixed $value): void
    {
        if (!\is_string($value) || '' === ($value = trim($value))) {
            return;
        }

        $qb->andWhere(\sprintf('LOWER(%s) LIKE :%s', $field, $param))
            ->setParameter($param, '%'.addcslashes(mb_strtolower($value), '%_\\').'%');
    }

    private function filterRange(QueryBuilder $qb, string $field, string $param, mixed $min, mixed $max): void
    {
        if (is_numeric($min)) {
            $qb->andWhere(\sprintf('%s >= :%sMin', $field, $param))->setParameter($param.'Min', $min);
        }

        if (is_numeric($max)) {
            $qb->andWhere(\sprintf('%s <= :%sMax', $field, $param))->setParameter($param.'Max', $max);
        }
    }

    private function filterDateRange(QueryBuilder $qb, string $field, string $param, mixed $from, mixed $to): void
    {
        if ($from instanceof \DateTimeInterface) {
            $qb->andWhere(\sprintf('%s >= :%sFrom', $field, $param))
                ->setParameter($param.'From', $from->format('Y-m-d').' 00:00:00');
        }

        if ($to instanceof \DateTimeInterface) {
            $nextDay = \DateTimeImmutable::createFromInterface($to)->modify('+1 day');
            $qb->andWhere(\sprintf('%s < :%sTo', $field, $param))
                ->setParameter($param.'To', $nextDay->format('Y-m-d').' 00:00:00');
        }
    }
}
