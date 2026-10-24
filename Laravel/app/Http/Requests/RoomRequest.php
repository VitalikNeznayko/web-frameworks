<?php

namespace App\Http\Requests;

use App\Models\Room;
use Illuminate\Validation\Rule;

class RoomRequest extends HostelRequest
{
    public function rules(): array
    {
        return [
            'number' => ['required', 'string', 'max:10', Rule::unique('rooms', 'number')->ignore($this->route('room'))],
            'type' => ['required', Rule::in(array_keys(Room::TYPES))],
            'floor' => ['required', 'integer', 'min:0', 'max:200'],
            'price_per_night' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'description' => ['nullable', 'string'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'number' => 'номер',
            'type' => 'тип',
            'floor' => 'поверх',
            'price_per_night' => 'ціна за ніч',
            'description' => 'опис',
            'amenities' => 'зручності',
            'amenities.*' => 'зручність',
        ];
    }
}
