<?php

namespace App\Form\Filter;

use Symfony\Component\Form\FormBuilderInterface;

class GuestFilterType extends AbstractFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addId($builder);
        $this->addText($builder, 'firstName', "Ім'я");
        $this->addText($builder, 'lastName', 'Прізвище');
        $this->addText($builder, 'email', 'Email');
        $this->addText($builder, 'phone', 'Телефон');
        $this->addText($builder, 'passportNumber', 'Номер паспорта');
        $this->addText($builder, 'country', 'Країна');
        $this->addDateRange($builder, 'birthDate', 'Дата народження');
    }
}
