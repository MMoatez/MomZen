<?php

namespace App\Form;

use App\Entity\Rendezvous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\User;

class RendezvousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date', DateTimeType::class, [
            'widget' => 'single_text',
            'html5' => true,
            'input' => 'datetime_immutable',
            'required' => true,
            'empty_data' => (new \DateTimeImmutable())->format('Y-m-d\TH:i:s'), // Ensures valid date
            'constraints' => [
                new Assert\NotBlank(['message' => 'Veuillez sélectionner une date.']),
                new Assert\Type([
                    'type' => \DateTimeImmutable::class,
                    'message' => 'La date doit être une valeur valide.',
                ]),
                new Assert\GreaterThan([
                    'value' => new \DateTimeImmutable(),
                    'message' => 'La date du rendez-vous doit être dans le futur.',
                ]),
            ],
        ])
        
        
        
            ->add('adresse', TextType::class, [
                //'required' => true,
                'required' => false,
                'attr' => ['class' => 'adresse-field'],
                'constraints' => [
                    /*new Assert\NotBlank([
                        'message' => 'Veuillez entrer une adresse valide.'
                  
                        ]), */
                    new Assert\Length([
                        'min' => 5,
                        'minMessage' => 'L\'adresse doit contenir au moins {{ limit }} caractères.'
                    ]),
                    new Assert\Callback([$this, 'validateAdresse'])
                ]
            ])
            ->add('domicile', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Rendez-vous à domicile ?',
                'constraints' => [
                    new Assert\NotNull([
                        'message' => 'Veuillez choisir une option pour le domicile.'
                    ]),
                    new Assert\Choice([
                        'choices' => [true, false],
                        'message' => 'Veuillez sélectionner une option valide (Oui ou Non).'
                    ])
                ]
            ])
            ->add('idmedecin', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getNom() . ' ' . $user->getPrenom();
                },
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles = :roles')
                        ->setParameter('roles', '["ROLE_DOCTEUR"]');
                },
                'placeholder' => 'Sélectionnez un médecin',
                'label' => 'Médecin',
                'required' => true,
                'constraints' => [
                    new Assert\NotNull([
                        'message' => 'Veuillez sélectionner un médecin.'
                    ])
                ]
            ]);
    }



    public function validateAdresse($adresse, ExecutionContextInterface $context)
    {
        $form = $context->getRoot(); // Récupérer le formulaire complet
        $domicile = $form->get('domicile')->getData(); // Récupérer la valeur de domicile

        if ($domicile === true && empty($adresse)) {
            $context->buildViolation('Veuillez entrer une adresse si le rendez-vous est à domicile.')
                ->atPath('adresse')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rendezvous::class,
        ]);
    }
}
