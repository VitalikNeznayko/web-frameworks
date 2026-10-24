<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class AmenityRequest extends HostelRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('amenities', 'name')->ignore($this->route('amenity'))],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'назва',
            'description' => 'опис',
        ];
    }
}
