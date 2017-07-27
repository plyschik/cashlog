<?php

namespace CashLog\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CashLog\Validator\ConfirmPassword;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ConfirmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Wpisz hasło.'
                        ]),
                        new ConfirmPassword()
                ],
                'attr' => [
                    'placeholder' => 'Hasło...'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Dalej',
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