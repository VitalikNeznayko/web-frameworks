<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Validation\Rule;

class BookingRequest extends HostelRequest
{
    public function rules(): array
    {
        return [
            'guest_id' => ['required', 'integer', 'exists:guests,id'],
            'bed_id' => ['required', 'integer', 'exists:beds,id'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'status' => ['required', Rule::in(array_keys(Booking::STATUSES))],
            'total_price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'guest_id' => 'гість',
            'bed_id' => 'ліжко',
            'check_in' => 'дата заїзду',
            'check_out' => 'дата виїзду',
            'status' => 'статус',
            'total_price' => 'загальна вартість',
            'notes' => 'примітки',
        ];
    }
}
