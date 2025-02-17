<?php

namespace App\Form;

use App\Entity\Forum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use Symfony\Component\Validator\Constraints\File;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le titre est obligatoire.']),
                    new Length([
                        'max' => 15,
                        'maxMessage' => 'Le titre ne doit pas dépasser 15 caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/\bzut\b/i',
                        'match' => false,
                        'message' => "Le mot 'zut' est interdit dans le titre.",
                    ]),
                ],
            ])
            ->add('contenu', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le contenu ne peut pas être vide.']),
                    new Regex([
                        'pattern' => '/\bzut\b/i',
                        'match' => false,
                        'message' => "Le mot 'zut' est interdit dans le contenu.",
                    ]),
                ],
            ])
            
    ->add('image', FileType::class, [
        'mapped' => false, // Ne pas mapper directement à l'entité
        'required' => false,
        'constraints' => [
            new File([
                'maxSize' => '2M',
                'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF).',
            ]),
        ],
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forum::class,
        ]);
    }
}
