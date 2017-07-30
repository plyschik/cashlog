<?php

namespace CashLog\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use CashLog\Validator\ValidPassword;
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
                    new Assert\NotBlank([
                        'message' => 'logRemove.form.password.notBlank'
                    ]),
                    new ValidPassword()
                ],
                'attr' => [
                    'placeholder' => 'logRemove.form.password.placeholder'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'logRemove.form.submit.label',
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