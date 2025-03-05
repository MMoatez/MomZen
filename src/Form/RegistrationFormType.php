<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('numTel')
            ->add('image')
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
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => true, // Ce champ ne modifie pas directement l'entité
                'required' => true, // Rendre le champ facultatif
                'attr' => ['class' => 'form-control'],
            ])
        
        
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Télécharger une image',
                'mapped' => false, // Indique que ce champ n'est pas directement lié à une propriété de l'entité
                'required' => false, // Permet de rendre le champ optionnel
                'attr' => [
                    'class' => 'form-control', // Applique une classe pour le style
                    'accept' => 'image/*', // Limite aux fichiers image
                ],
            ])
            ->add("recaptcha", ReCaptchaType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
