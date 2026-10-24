<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;

class UserRequest extends HostelRequest
{
    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'role' => ['required', Rule::in($this->isSelf() ? [$user->role] : array_keys(User::ROLES))],
            'password' => [$user instanceof User ? 'nullable' : 'required', 'string', 'min:8', 'max:255', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        $messages = parent::messages();

        if ($this->isSelf()) {
            $messages['role.in'] = 'Ви не можете змінити власну роль.';
        }

        return $messages;
    }

    public function attributes(): array
    {
        return [
            'name' => "ім'я",
            'email' => 'email',
            'role' => 'роль',
            'password' => 'пароль',
        ];
    }

    private function isSelf(): bool
    {
        $user = $this->route('user');

        return $user instanceof User && $this->user()?->is($user) === true;
    }
}
