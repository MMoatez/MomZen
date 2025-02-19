<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('ordonnance', TextareaType::class, [
            'required' => true,
            'attr' => ['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Entrez l\'ordonnance...'],
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'L\'ordonnance ne peut pas être vide.',
                ]),
                new Assert\Length([
                    'min' => 10,
                    'minMessage' => 'L\'ordonnance doit contenir au moins {{ limit }} caractères.',
                ]),
            ],
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
