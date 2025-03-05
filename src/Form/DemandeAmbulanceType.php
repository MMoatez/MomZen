<?php

namespace App\Form;

use App\Entity\DemandeAmbulance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DemandeAmbulanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('patientName', TextType::class, [
                'label' => 'Nom du patient'
            ])
            ->add('adresse', TextareaType::class, [
                'label' => 'Adresse de prise en charge'
            ])
            ->add('urgenceLevel', ChoiceType::class, [
                'label' => "Niveau d'urgence",
                'choices' => [
                    'Urgence vitale' => 'URGENCE_VITALE',
                    'Urgence relative' => 'URGENCE_RELATIVE',
                    'Transport simple' => 'TRANSPORT_SIMPLE'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeAmbulance::class,
        ]);
    }
}