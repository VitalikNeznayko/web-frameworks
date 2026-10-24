<?php

namespace App\Form\Filter;

use App\Entity\Bed;
use App\Entity\Room;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class BedFilterType extends AbstractFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addId($builder);
        $builder->add('room', EntityType::class, [
            'label' => 'Кімната',
            'class' => Room::class,
            'placeholder' => 'Усі',
            'query_builder' => static fn (EntityRepository $er) => $er->createQueryBuilder('r')->orderBy('r.number', 'ASC'),
            'invalid_message' => 'Невідоме значення.',
        ]);
        $this->addText($builder, 'label', 'Позначка');
        $this->addChoice($builder, 'bedType', 'Тип', Bed::TYPE_LABELS);
        $builder->add('isActive', ChoiceType::class, [
            'label' => 'Активне',
            'choices' => ['Так' => '1', 'Ні' => '0'],
            'placeholder' => 'Усі',
            'invalid_message' => 'Невідоме значення.',
        ]);
    }
}
