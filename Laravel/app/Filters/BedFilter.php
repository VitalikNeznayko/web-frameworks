<?php

namespace App\Filters;

use App\Models\Bed;
use App\Models\Room;

class BedFilter extends QueryFilter
{
    protected function fields(): array
    {
        return [
            'id' => ['type' => 'exact', 'label' => 'ID'],
            'room_id' => [
                'type' => 'select',
                'label' => 'Кімната',
                'options' => fn () => Room::orderBy('number')->pluck('number', 'id')->all(),
            ],
            'label' => ['type' => 'text', 'label' => 'Позначка'],
            'bed_type' => ['type' => 'enum', 'label' => 'Тип', 'options' => Bed::TYPES],
            'is_active' => ['type' => 'boolean', 'label' => 'Активне'],
            'created_at' => ['type' => 'date', 'label' => 'Створено'],
        ];
    }
}
