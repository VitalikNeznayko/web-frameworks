<?php

namespace App\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'required' => false,
            'attr' => ['class' => 'filter-form'],
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    protected function addId(FormBuilderInterface $builder): void
    {
        $builder->add('id', IntegerType::class, [
            'label' => 'ID',
            'invalid_message' => 'Введіть ціле число.',
            'attr' => ['min' => 1],
        ]);
    }

    protected function addText(FormBuilderInterface $builder, string $name, string $label): void
    {
        $builder->add($name, TextType::class, [
            'label' => $label,
            'attr' => ['placeholder' => 'містить…'],
        ]);
    }

    /**
     * @param array<string, string> $labels
     */
    protected function addChoice(FormBuilderInterface $builder, string $name, string $label, array $labels): void
    {
        $builder->add($name, ChoiceType::class, [
            'label' => $label,
            'choices' => array_flip($labels),
            'placeholder' => 'Усі',
            'invalid_message' => 'Невідоме значення.',
        ]);
    }

    protected function addIntegerRange(FormBuilderInterface $builder, string $name, string $label): void
    {
        foreach (['Min' => 'від', 'Max' => 'до'] as $suffix => $word) {
            $builder->add($name.$suffix, IntegerType::class, [
                'label' => $label.' '.$word,
                'invalid_message' => 'Введіть ціле число.',
            ]);
        }
    }

    protected function addNumberRange(FormBuilderInterface $builder, string $name, string $label): void
    {
        foreach (['Min' => 'від', 'Max' => 'до'] as $suffix => $word) {
            $builder->add($name.$suffix, NumberType::class, [
                'label' => $label.' '.$word,
                'scale' => 2,
                'html5' => true,
                'invalid_message' => 'Введіть число.',
                'attr' => ['step' => '0.01', 'min' => 0],
            ]);
        }
    }

    protected function addDateRange(FormBuilderInterface $builder, string $name, string $label): void
    {
        foreach (['From' => 'від', 'To' => 'до'] as $suffix => $word) {
            $builder->add($name.$suffix, DateType::class, [
                'label' => $label.' '.$word,
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'invalid_message' => 'Введіть коректну дату.',
            ]);
        }
    }
}
