<?php

namespace App\Form\Filter;

use App\Entity\Bed;
use App\Entity\Booking;
use App\Entity\Guest;
use App\Entity\Room;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class BookingFilterType extends AbstractFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addId($builder);
        $builder
            ->add('guest', EntityType::class, [
                'label' => 'Гість',
                'class' => Guest::class,
                'placeholder' => 'Усі',
                'query_builder' => static fn (EntityRepository $er) => $er->createQueryBuilder('g')->orderBy('g.lastName', 'ASC')->addOrderBy('g.firstName', 'ASC'),
                'invalid_message' => 'Невідоме значення.',
            ])
            ->add('room', EntityType::class, [
                'label' => 'Кімната',
                'class' => Room::class,
                'placeholder' => 'Усі',
                'query_builder' => static fn (EntityRepository $er) => $er->createQueryBuilder('r')->orderBy('r.number', 'ASC'),
                'invalid_message' => 'Невідоме значення.',
            ])
            ->add('bed', EntityType::class, [
                'label' => 'Ліжко',
                'class' => Bed::class,
                'placeholder' => 'Усі',
                'query_builder' => static fn (EntityRepository $er) => $er->createQueryBuilder('b')->join('b.room', 'r')->addSelect('r')->orderBy('r.number', 'ASC')->addOrderBy('b.label', 'ASC'),
                'invalid_message' => 'Невідоме значення.',
            ]);
        $this->addDateRange($builder, 'checkIn', 'Заїзд');
        $this->addDateRange($builder, 'checkOut', 'Виїзд');
        $this->addChoice($builder, 'status', 'Статус', Booking::STATUS_LABELS);
        $this->addNumberRange($builder, 'totalPrice', 'Вартість');
        $this->addText($builder, 'notes', 'Примітки');
        $this->addDateRange($builder, 'createdAt', 'Створено');
    }
}
