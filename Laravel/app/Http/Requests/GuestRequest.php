<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class GuestRequest extends HostelRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('guests', 'email')->ignore($this->route('guest'))],
            'phone' => ['required', 'string', 'max:30'],
            'passport_number' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date', 'before:today'],
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => "ім'я",
            'last_name' => 'прізвище',
            'email' => 'email',
            'phone' => 'телефон',
            'passport_number' => 'номер паспорта',
            'country' => 'країна',
            'birth_date' => 'дата народження',
        ];
    }
}
