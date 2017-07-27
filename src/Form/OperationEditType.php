<?php

namespace CashLog\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use CashLog\Validator\ConfirmPassword;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class OperationEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Opis...'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Opis jest polem wymaganym.'
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'max' => 64,
                        'minMessage' => 'Opis musi zawierać conajmniej 8 znaków.',
                        'maxMessage' => 'Opis może mieć maksymalnie 64 znaki.'
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Hasło...'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Wpisz hasło.'
                    ]),
                    new ConfirmPassword()
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
                'class' => 'ui form',
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}