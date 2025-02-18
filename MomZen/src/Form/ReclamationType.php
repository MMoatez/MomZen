<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter the title of your reclamation'
                ],
                'label' => 'Title',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a title'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Title should be at least {{ limit }} characters long',
                        'max' => 255,
                        'maxMessage' => 'Title cannot be longer than {{ limit }} characters'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '5',
                    'placeholder' => 'Describe your issue in detail'
                ],
                'label' => 'Description',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please provide a description'
                    ]),
                    new Length([
                        'min' => 20,
                        'minMessage' => 'Description should be at least {{ limit }} characters long',
                        'max' => 1000,
                        'maxMessage' => 'Description cannot be longer than {{ limit }} characters'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
