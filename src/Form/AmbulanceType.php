<?php

// src/Form/AmbulanceType.php
namespace App\Form;

use App\Entity\Ambulance;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AmbulanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('immatriculation', TextType::class, [
                'label' => 'Immatriculation',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Ex: 1234-ABC'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('marque', TextType::class, [
                'label' => 'Marque',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Ex: Mercedes'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('modele', TextType::class, [
                'label' => 'Modèle',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Ex: Sprinter'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('chauffeur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',  // Choisissez 'email' ou 'nom'
                'label' => 'Chauffeur',
                'attr' => [
                    'class' => 'form-select form-select-lg'
                ],
                'placeholder' => 'Sélectionner un chauffeur',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary btn-lg mt-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ambulance::class,
        ]);
    }
}
