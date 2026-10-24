<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Illuminate\Validation\Rule;

class PaymentRequest extends HostelRequest
{
    public function rules(): array
    {
        return [
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'method' => ['required', Rule::in(array_keys(Payment::METHODS))],
            'status' => ['required', Rule::in(array_keys(Payment::STATUSES))],
            'paid_at' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'booking_id' => 'бронювання',
            'amount' => 'сума',
            'method' => 'спосіб оплати',
            'status' => 'статус',
            'paid_at' => 'дата оплати',
        ];
    }
}
