<?php

namespace CashLog\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use CashLog\Validator\ValidPassword;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Wpisz hasło.'
                    ]),
                    new ValidPassword()
                ],
                'attr' => [
                    'placeholder' => 'Hasło...'
                ]
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Wpisz nowe hasło.'
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Nowe hasło powinno mieć conajmniej 8 znaków.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Nowe hasło...'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zmień hasło',
                'attr' => [
                    'class' => 'ui button'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'ui form'
            ]
        ]);
    }
}