<?php

namespace App\Form;

use App\Entity\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '5',
                    'placeholder' => 'Enter your response here'
                ],
                'label' => 'Response',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a response'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Response should be at least {{ limit }} characters long',
                        'max' => 1000,
                        'maxMessage' => 'Response cannot be longer than {{ limit }} characters'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Response::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
} 