<?php

namespace App\Form;

use App\Entity\Bed;
use App\Entity\Room;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('room', EntityType::class, [
                'label' => 'Кімната',
                'class' => Room::class,
                'placeholder' => 'Оберіть кімнату',
                'query_builder' => fn (EntityRepository $er) => $er->createQueryBuilder('r')->orderBy('r.number', 'ASC'),
            ])
            ->add('label', TextType::class, [
                'label' => 'Позначка ліжка',
                'attr' => ['placeholder' => 'A1'],
            ])
            ->add('bedType', ChoiceType::class, [
                'label' => 'Тип ліжка',
                'choices' => array_flip(Bed::TYPE_LABELS),
                'placeholder' => 'Оберіть тип',
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Активне',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bed::class,
        ]);
    }
}
