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
                        'message' => 'passwordChange.form.password.validator.notBlank'
                    ]),
                    new ValidPassword()
                ],
                'attr' => [
                    'placeholder' => 'passwordChange.form.password.placeholder'
                ]
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'passwordChange.form.newPassword.validator.notBlank'
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'passwordChange.form.newPassword.validator.minLength'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'passwordChange.form.newPassword.placeholder'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'passwordChange.form.submit.label',
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