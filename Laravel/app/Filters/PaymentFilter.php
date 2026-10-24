<?php

namespace App\Filters;

use App\Models\Booking;
use App\Models\Payment;

class PaymentFilter extends QueryFilter
{
    protected function fields(): array
    {
        return [
            'id' => ['type' => 'exact', 'label' => 'ID'],
            'booking_id' => [
                'type' => 'select',
                'label' => 'Бронювання',
                'options' => fn () => Booking::with('guest')->orderByDesc('id')->get()
                    ->mapWithKeys(fn (Booking $booking) => [$booking->id => '#'.$booking->id.' — '.$booking->guest->full_name])
                    ->all(),
            ],
            'amount' => ['type' => 'range', 'label' => 'Сума, грн', 'step' => '0.01'],
            'method' => ['type' => 'enum', 'label' => 'Спосіб', 'options' => Payment::METHODS],
            'status' => ['type' => 'enum', 'label' => 'Статус', 'options' => Payment::STATUSES],
            'paid_at' => ['type' => 'date', 'label' => 'Дата оплати'],
            'created_at' => ['type' => 'date', 'label' => 'Створено'],
        ];
    }
}
