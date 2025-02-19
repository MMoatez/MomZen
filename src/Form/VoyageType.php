<?php

namespace App\Form;

use App\Entity\Voyage;
use App\Entity\Ambulance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date_depart', DateType::class, [
            'label' => 'date_depart',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',  // Format de date attendu
            'html5' => false,
            'attr' => [
                'class' => 'col-12 col-sm-6 py-2 wow fadeInLeft',
                'placeholder' => 'jj/mm/aaaa',
            ],
            'label_attr' => [
                'class' => 'form-label'
            ]
        ])
            ->add('emplacement_client', TextType::class, [
                'label' => 'emplacement_client',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Ex: Hay tahrir'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('ambulance', EntityType::class, [
                'class' => ambulance::class,
                'choice_label' => 'immatriculation',
                'label' => 'immatriculation',
                'attr' => [
                    'class' => 'form-select form-select-lg'
                ],
                'placeholder' => 'Sélectionner une ambulance',
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
            'data_class' => voyage::class,
        ]);
    }
}
