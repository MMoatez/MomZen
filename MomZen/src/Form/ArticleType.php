<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter article title'
                ],
                'label' => 'Title',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a title'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Title should be at least {{ limit }} characters long',
                        'max' => 255,
                        'maxMessage' => 'Title cannot be longer than {{ limit }} characters'
                    ])
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '10',
                    'data-editor' => 'true'
                ],
                'label' => 'Content',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter some content'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Content should be at least {{ limit }} characters long'
                    ])
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG or PNG image',
                        'maxSizeMessage' => 'The file is too large ({{ size }} {{ suffix }}). Maximum allowed size is {{ limit }} {{ suffix }}.'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/jpeg,image/png'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Draft' => 'draft',
                    'Published' => 'published'
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Status',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a status'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'attr' => [
                'novalidate' => 'novalidate', // This allows HTML5 validation to be handled by Symfony
            ],
        ]);
    }
} 