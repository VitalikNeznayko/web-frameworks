<?php

namespace App\Form\Filter;

use App\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;

class UserFilterType extends AbstractFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addId($builder);
        $this->addText($builder, 'email', 'Email');
        $this->addText($builder, 'fullName', "Повне ім'я");
        $this->addChoice($builder, 'role', 'Роль', User::ROLE_LABELS);
        $this->addDateRange($builder, 'createdAt', 'Створено');
    }
}
