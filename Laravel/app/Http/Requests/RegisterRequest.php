<?php

namespace App\Http\Requests;

class RegisterRequest extends HostelRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => "ім'я",
            'email' => 'email',
            'password' => 'пароль',
        ];
    }
}
