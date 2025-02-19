<?php

namespace App\Form;

use App\Entity\Dossiermedical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class DossiermedicalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('historique', TextareaType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Entrez l\'historique médical...'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'L\'historique médical ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'min' => 10,
                        'minMessage' => 'L\'historique doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dossiermedical::class,
        ]);
    }
}
