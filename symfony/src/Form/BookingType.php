<?php

namespace App\Form;

use App\Entity\Bed;
use App\Entity\Booking;
use App\Entity\Guest;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('guest', EntityType::class, [
                'label' => 'Гість',
                'class' => Guest::class,
                'placeholder' => 'Оберіть гостя',
                'query_builder' => fn (EntityRepository $er) => $er->createQueryBuilder('g')->orderBy('g.lastName', 'ASC'),
            ])
            ->add('bed', EntityType::class, [
                'label' => 'Ліжко',
                'class' => Bed::class,
                'placeholder' => 'Оберіть ліжко',
                'query_builder' => fn (EntityRepository $er) => $er->createQueryBuilder('b')
                    ->join('b.room', 'r')
                    ->addSelect('r')
                    ->orderBy('r.number', 'ASC')
                    ->addOrderBy('b.label', 'ASC'),
            ])
            ->add('checkIn', DateType::class, [
                'label' => 'Дата заїзду',
                'widget' => 'single_text',
            ])
            ->add('checkOut', DateType::class, [
                'label' => 'Дата виїзду',
                'widget' => 'single_text',
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Статус',
                'choices' => array_flip(Booking::STATUS_LABELS),
            ])
            ->add('totalPrice', NumberType::class, [
                'label' => 'Загальна вартість, грн',
                'scale' => 2,
                'input' => 'string',
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Примітки',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
