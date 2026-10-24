<?php

namespace App\Filters;

use App\Models\Bed;
use App\Models\Booking;
use App\Models\Guest;

class BookingFilter extends QueryFilter
{
    protected function fields(): array
    {
        return [
            'id' => ['type' => 'exact', 'label' => 'ID'],
            'guest_id' => [
                'type' => 'select',
                'label' => 'Гість',
                'options' => fn () => Guest::orderBy('last_name')->orderBy('first_name')->get()
                    ->mapWithKeys(fn (Guest $guest) => [$guest->id => $guest->full_name])
                    ->all(),
            ],
            'bed_id' => [
                'type' => 'select',
                'label' => 'Ліжко',
                'options' => fn () => Bed::with('room')->orderBy('room_id')->orderBy('label')->get()
                    ->mapWithKeys(fn (Bed $bed) => [$bed->id => 'Кімната '.$bed->room->number.', ліжко '.$bed->label])
                    ->all(),
            ],
            'check_in' => ['type' => 'date', 'label' => 'Дата заїзду'],
            'check_out' => ['type' => 'date', 'label' => 'Дата виїзду'],
            'status' => ['type' => 'enum', 'label' => 'Статус', 'options' => Booking::STATUSES],
            'total_price' => ['type' => 'range', 'label' => 'Вартість, грн', 'step' => '0.01'],
            'notes' => ['type' => 'text', 'label' => 'Примітки'],
            'created_at' => ['type' => 'date', 'label' => 'Створено'],
        ];
    }
}
