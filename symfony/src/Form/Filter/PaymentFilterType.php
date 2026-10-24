<?php

namespace App\Form\Filter;

use App\Entity\Booking;
use App\Entity\Payment;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class PaymentFilterType extends AbstractFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addId($builder);
        $builder->add('booking', EntityType::class, [
            'label' => 'Бронювання',
            'class' => Booking::class,
            'placeholder' => 'Усі',
            'query_builder' => static fn (EntityRepository $er) => $er->createQueryBuilder('bk')
                ->join('bk.guest', 'g')
                ->join('bk.bed', 'b')
                ->join('b.room', 'r')
                ->addSelect('g', 'b', 'r')
                ->orderBy('bk.id', 'DESC'),
            'invalid_message' => 'Невідоме значення.',
        ]);
        $this->addNumberRange($builder, 'amount', 'Сума');
        $this->addChoice($builder, 'method', 'Спосіб', Payment::METHOD_LABELS);
        $this->addChoice($builder, 'status', 'Статус', Payment::STATUS_LABELS);
        $this->addDateRange($builder, 'paidAt', 'Дата оплати');
    }
}
