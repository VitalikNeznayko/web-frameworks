<?php

namespace App\Http\Requests;

use App\Models\Bed;
use Illuminate\Validation\Rule;

class BedRequest extends HostelRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }

    public function rules(): array
    {
        return [
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'label' => [
                'required',
                'string',
                'max:10',
                Rule::unique('beds', 'label')
                    ->where(fn ($query) => $query->where('room_id', $this->input('room_id')))
                    ->ignore($this->route('bed')),
            ],
            'bed_type' => ['required', Rule::in(array_keys(Bed::TYPES))],
            'is_active' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'room_id' => 'кімната',
            'label' => 'позначка',
            'bed_type' => 'тип ліжка',
            'is_active' => 'активне',
        ];
    }
}
