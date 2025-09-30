<?php
namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Identité
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez votre nom'],
                'constraints' => [ new NotBlank(message: 'Veuillez entrer votre nom') ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Entrez votre prénom'],
                'constraints' => [ new NotBlank(message: 'Veuillez entrer votre prénom') ],
            ])

            // Contact
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Entrez votre email'],
                'constraints' => [ new NotBlank(message: 'Veuillez entrer votre email') ],
            ])

            // Adresse postale
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['placeholder' => '12 rue des Fleurs'],
                'constraints' => [ new NotBlank(message: 'Veuillez entrer votre adresse') ],
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'attr' => ['placeholder' => '75001'],
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre code postal'),
                    new Length(min: 4, max: 10, minMessage: 'Code postal trop court'),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Paris'],
                'constraints' => [ new NotBlank(message: 'Veuillez entrer votre ville') ],
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'placeholder' => 'Sélectionnez un pays',
                'preferred_choices' => ['FR'],
                'constraints' => [ new NotBlank(message: 'Veuillez choisir un pays') ],
            ])

            // Mot de passe (répété)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => ['placeholder' => 'Entrez votre mot de passe'],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
                ],
                'invalid_message' => 'Les deux mots de passe doivent correspondre',
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer un mot de passe'),
                    new Length(min: 8, minMessage: 'Au moins 8 caractères'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            // 'translation_domain' => 'messages', // si tu utilises les traductions
        ]);
    }
}
