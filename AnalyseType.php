<?php

namespace App\Form;

use App\Entity\Analyse;
use App\Entity\DossierMedical;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnalyseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('risqueGrosses', null, [
                'label' => 'Risque de grossesse',
            ])
            ->add('urgence_medicale', null, [
                'label' => 'Urgence médicale',
            ])
            ->add('dossier_medicale', EntityType::class, [
                'class' => DossierMedical::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Analyse::class,
        ]);
    }
}
