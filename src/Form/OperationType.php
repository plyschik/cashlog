<?php

namespace CashLog\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => false,
                'invalid_message' => 'newOperationLog.form.type.invalidType',
                'choices' => [
                    'newOperationLog.form.type.choices.in' => 0,
                    'newOperationLog.form.type.choices.out' => 1
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'newOperationLog.form.type.invalidType'
                    ]),
                    new Assert\Choice([
                        'choices' => [0, 1],
                        'message' => 'newOperationLog.form.type.invalidType'
                    ])
                ],
                'attr' => [
                    'class' => 'ui dropdown fluid'
                ]
            ])
            ->add('description', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'newOperationLog.form.description.placeholder'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'newOperationLog.form.description.notBlank'
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'max' => 64,
                        'minMessage' => 'newOperationLog.form.description.minMessage',
                        'maxMessage' => 'newOperationLog.form.description.maxMessage'
                    ])
                ]
            ])
            ->add('cash', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'newOperationLog.form.cash.placeholder'
                ],
                'invalid_message' => 'newOperationLog.form.cash.invalidMessage',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'newOperationLog.form.cash.notBlank'
                    ]),
                    new Assert\GreaterThan([
                        'value' => 0,
                        'message' => 'newOperationLog.form.cash.invalidMessage'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{1,6}(?:\.[0-9]{0,2})?$/',
                        'message' => 'newOperationLog.form.cash.invalidMessage'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'newOperationLog.form.submit.label',
                'attr' => [
                    'class' => 'ui button fluid'
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