<?php

namespace CashLog\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use CashLog\Validator\ValidPassword;
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
                    'placeholder' => 'logEdit.form.description.placeholder'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'logEdit.form.description.validator.notBlank'
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'max' => 64,
                        'minMessage' => 'logEdit.form.description.validator.minLength',
                        'maxMessage' => 'logEdit.form.description.validator.maxLength'
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'logEdit.form.password.placeholder'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'logEdit.form.password.validator.notBlank'
                    ]),
                    new ValidPassword()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'logEdit.form.submit.label',
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