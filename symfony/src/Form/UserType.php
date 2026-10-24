<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $passwordConstraints = [
            new Assert\Length(min: 6, max: 4096, minMessage: 'Пароль має містити щонайменше {{ limit }} символів.'),
        ];
        if ($options['require_password']) {
            $passwordConstraints[] = new Assert\NotBlank(message: 'Введіть пароль.');
        }

        $builder
            ->add('fullName', TextType::class, [
                'label' => "Повне ім'я",
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Роль',
                'choices' => array_flip(User::ROLE_LABELS),
                'disabled' => $options['lock_role'],
                'help' => $options['lock_role'] ? 'Ви не можете змінити власну роль.' : null,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => $options['require_password'],
                'invalid_message' => 'Паролі не збігаються.',
                'first_options' => [
                    'label' => 'Пароль',
                    'help' => $options['require_password'] ? null : 'Залиште порожнім, щоб не змінювати.',
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                'second_options' => ['label' => 'Повторіть пароль', 'attr' => ['autocomplete' => 'new-password']],
                'constraints' => $passwordConstraints,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'require_password' => true,
            'lock_role' => false,
        ]);
        $resolver->setAllowedTypes('require_password', 'bool');
        $resolver->setAllowedTypes('lock_role', 'bool');
    }
}
