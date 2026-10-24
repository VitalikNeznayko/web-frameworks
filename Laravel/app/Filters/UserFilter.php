<?php

namespace App\Filters;

use App\Models\User;

class UserFilter extends QueryFilter
{
    protected function fields(): array
    {
        return [
            'id' => ['type' => 'exact', 'label' => 'ID'],
            'name' => ['type' => 'text', 'label' => "Ім'я"],
            'email' => ['type' => 'text', 'label' => 'Email'],
            'role' => ['type' => 'enum', 'label' => 'Роль', 'options' => User::ROLES],
            'created_at' => ['type' => 'date', 'label' => 'Зареєстровано'],
        ];
    }
}
