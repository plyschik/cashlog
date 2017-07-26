<?php

namespace CashLog\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => false,
                'invalid_message' => 'Wybrany typ operacji jest niepoprawny.',
                'choices' => [
                    'WPŁATA' => 0,
                    'WYPŁATA' => 1
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Wybrany typ operacji nie istnieje.'
                    ]),
                    new Assert\Choice([
                        'choices' => [0, 1],
                        'message' => 'Wybierz poprawny typ operacji.'
                    ])
                ]
            ])
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
            ->add('cash', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Kwota...'
                ],
                'invalid_message' => 'Wpisana kwota jest niepoprawna.',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Kwota jest polem wymaganym.'
                    ]),
                    new Assert\GreaterThan([
                        'value' => 0,
                        'message' => 'Kwota powinna być liczbą większą od zera.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{1,6}(?:\.[0-9]{0,2})?$/',
                        'message' => 'Wpisana kwota nie pasuje do wzoru. Kropka zamiast przecinka.'
                    ])
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