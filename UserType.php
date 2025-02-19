<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    'Docteur' => 'ROLE_DOCTEUR',
                    'Chauffeur' => 'ROLE_CHAUFFEUR',
                ],
                'expanded' => true,  // Liste déroulante
                'multiple' => true,  // Permet de sélectionner plusieurs rôles
                'placeholder' => 'Sélectionnez un  rôles',
                'attr' => ['class' => 'form-select'],
            ])
            
            
            ->add('nom')
            ->add('prenom')
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => true, // Ce champ ne modifie pas directement l'entité
                'required' => true, // Rendre le champ facultatif
                'attr' => ['class' => 'form-control'],
            ])
        
            ->add('numTel')
            ->add('genre', ChoiceType::class, [
                'choices'  => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                ],
                'expanded' => false, // Liste déroulante (select)
                'multiple' => false, // Un seul choix possible
                'placeholder' => 'Sélectionnez votre genre',
                'attr' => ['class' => 'form-select'],
            ])           
            ->add('image', FileType::class, [
                'label' => 'Télécharger une image',
                'mapped' => false, // Indique que ce champ n'est pas directement lié à une propriété de l'entité
                'required' => false, // Permet de rendre le champ optionnel
                'attr' => [
                    'class' => 'form-control', // Applique une classe pour le style
                    'accept' => 'image/*', // Limite aux fichiers image
                ],
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
