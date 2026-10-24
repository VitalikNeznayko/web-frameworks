<?php

namespace App\Filters;

class AmenityFilter extends QueryFilter
{
    protected function fields(): array
    {
        return [
            'id' => ['type' => 'exact', 'label' => 'ID'],
            'name' => ['type' => 'text', 'label' => 'Назва'],
            'description' => ['type' => 'text', 'label' => 'Опис'],
            'created_at' => ['type' => 'date', 'label' => 'Створено'],
        ];
    }
}
