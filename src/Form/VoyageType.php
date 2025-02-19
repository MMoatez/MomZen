<?php
// src/Form/VoyageType.php
namespace App\Form;

use App\Entity\Voyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Ambulance;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ambulance', EntityType::class, [
                'class' => Ambulance::class,
                'choice_label' => 'immatriculation',
                'placeholder' => 'Choisir une ambulance',
            ])
            ->add('date_depart', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('emplacement_client', TextType::class, [
                'label' => 'Emplacement du client',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
        ]);
    }
}