<?php

namespace App\Http\Requests;

class LoginRequest extends HostelRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'email',
            'password' => 'пароль',
        ];
    }
}
