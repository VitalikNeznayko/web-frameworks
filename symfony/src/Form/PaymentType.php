<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Payment;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('booking', EntityType::class, [
                'label' => 'Бронювання',
                'class' => Booking::class,
                'placeholder' => 'Оберіть бронювання',
                'query_builder' => fn (EntityRepository $er) => $er->createQueryBuilder('bk')
                    ->join('bk.guest', 'g')
                    ->join('bk.bed', 'b')
                    ->join('b.room', 'r')
                    ->addSelect('g', 'b', 'r')
                    ->orderBy('bk.id', 'DESC'),
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Сума, грн',
                'scale' => 2,
                'input' => 'string',
            ])
            ->add('method', ChoiceType::class, [
                'label' => 'Спосіб оплати',
                'choices' => array_flip(Payment::METHOD_LABELS),
                'placeholder' => 'Оберіть спосіб',
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Статус',
                'choices' => array_flip(Payment::STATUS_LABELS),
            ])
            ->add('paidAt', DateTimeType::class, [
                'label' => 'Дата оплати',
                'widget' => 'single_text',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
