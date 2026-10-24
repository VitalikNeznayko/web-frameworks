<?php

namespace App\Form\Filter;

use App\Entity\Room;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class AmenityFilterType extends AbstractFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addId($builder);
        $this->addText($builder, 'name', 'Назва');
        $this->addText($builder, 'description', 'Опис');
        $builder->add('room', EntityType::class, [
            'label' => 'Кімната',
            'class' => Room::class,
            'placeholder' => 'Усі',
            'query_builder' => static fn (EntityRepository $er) => $er->createQueryBuilder('r')->orderBy('r.number', 'ASC'),
            'invalid_message' => 'Невідоме значення.',
        ]);
    }
}
