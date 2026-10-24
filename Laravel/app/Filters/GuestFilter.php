<?php

namespace App\Filters;

class GuestFilter extends QueryFilter
{
    protected function fields(): array
    {
        return [
            'id' => ['type' => 'exact', 'label' => 'ID'],
            'first_name' => ['type' => 'text', 'label' => 'Імʼя'],
            'last_name' => ['type' => 'text', 'label' => 'Прізвище'],
            'email' => ['type' => 'text', 'label' => 'Email'],
            'phone' => ['type' => 'text', 'label' => 'Телефон'],
            'passport_number' => ['type' => 'text', 'label' => 'Номер паспорта'],
            'country' => ['type' => 'text', 'label' => 'Країна'],
            'birth_date' => ['type' => 'date', 'label' => 'Дата народження'],
            'created_at' => ['type' => 'date', 'label' => 'Створено'],
        ];
    }
}
