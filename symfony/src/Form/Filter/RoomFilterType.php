<?php

namespace App\Form\Filter;

use App\Entity\Amenity;
use App\Entity\Room;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class RoomFilterType extends AbstractFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addId($builder);
        $this->addText($builder, 'number', 'Номер');
        $this->addChoice($builder, 'type', 'Тип', Room::TYPE_LABELS);
        $this->addIntegerRange($builder, 'floor', 'Поверх');
        $this->addNumberRange($builder, 'pricePerNight', 'Ціна за ніч');
        $this->addText($builder, 'description', 'Опис');
        $builder->add('amenity', EntityType::class, [
            'label' => 'Зручність',
            'class' => Amenity::class,
            'choice_label' => 'name',
            'placeholder' => 'Усі',
            'query_builder' => static fn (EntityRepository $er) => $er->createQueryBuilder('a')->orderBy('a.name', 'ASC'),
            'invalid_message' => 'Невідоме значення.',
        ]);
    }
}
