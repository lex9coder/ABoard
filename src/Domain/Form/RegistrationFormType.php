<?php

namespace App\Domain\Form;

use App\Domain\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', null, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Имя'
                ]
            ]
            )
            ->add('lastName', null, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Фамилия'
                ]
            ]
            )
            ->add('email', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ]
            )
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'mapped' => false,
                'error_bubbling' => true,
                'invalid_message' => 'Пароли не совпадают',
                'required' => true,
                'first_options'  => array(
                    'label' => false,
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
                        ])
                    ]
                ),
                'second_options' => array('label' => false, 'attr' => ['placeholder' => 'Повторите пароль']),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
