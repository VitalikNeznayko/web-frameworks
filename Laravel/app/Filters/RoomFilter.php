<?php

namespace App\Filters;

use App\Models\Amenity;
use App\Models\Room;

class RoomFilter extends QueryFilter
{
    protected function fields(): array
    {
        return [
            'id' => ['type' => 'exact', 'label' => 'ID'],
            'number' => ['type' => 'text', 'label' => 'Номер'],
            'type' => ['type' => 'enum', 'label' => 'Тип', 'options' => Room::TYPES],
            'floor' => ['type' => 'range', 'label' => 'Поверх', 'step' => '1'],
            'price_per_night' => ['type' => 'range', 'label' => 'Ціна за ніч, грн', 'step' => '0.01'],
            'description' => ['type' => 'text', 'label' => 'Опис'],
            'amenity_id' => [
                'type' => 'relation',
                'label' => 'Зручність',
                'relation' => 'amenities',
                'options' => fn () => Amenity::orderBy('name')->pluck('name', 'id')->all(),
            ],
            'created_at' => ['type' => 'date', 'label' => 'Створено'],
        ];
    }
}
