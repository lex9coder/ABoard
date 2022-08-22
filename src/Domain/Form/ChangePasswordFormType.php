<?php

namespace App\Domain\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'error_bubbling' => true,
                'first_options' => [
                    'attr' => ['placeholder' => 'Пароль'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Введите пароль',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Пароль должен быть не менее {{ limit }} символов',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => false,
                ],
                'second_options' => [
                    'attr' => ['placeholder' => 'Повторите пароль'],
                    'label' => false,
                ],
                'invalid_message' => 'Пароли не совпадают',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
