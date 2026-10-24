<?php

namespace App\Form;

use App\Entity\Amenity;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, [
                'label' => 'Номер кімнати',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Тип',
                'choices' => array_flip(Room::TYPE_LABELS),
                'placeholder' => 'Оберіть тип',
            ])
            ->add('floor', IntegerType::class, [
                'label' => 'Поверх',
            ])
            ->add('pricePerNight', NumberType::class, [
                'label' => 'Ціна за ніч, грн',
                'scale' => 2,
                'input' => 'string',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Опис',
                'required' => false,
            ])
            ->add('amenities', EntityType::class, [
                'label' => 'Зручності',
                'class' => Amenity::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
