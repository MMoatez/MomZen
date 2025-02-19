<?php

namespace App\Form;

use App\Entity\DossierMedicall;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DossierMedicallType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('grosses_semaine', null, [
                'label' => 'Grossesse semaine',
            ])
            ->add('symptotes', null, [
                'label' => 'Symptômes',
            ])
            ->add('patient', EntityType::class, [
                'class' => User::class,
'choice_label' => 'nom',
                'label' => 'Patient',
                'placeholder' => 'Choisir un patient',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DossierMedicall::class,
        ]);
    }
}
