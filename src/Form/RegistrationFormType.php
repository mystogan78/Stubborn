<?php
namespace App\Form;
use App\Entity\Utilisateur; // 👈 IMPORTANT

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez votre nom'],
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre nom'),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Entrez votre prénom'],
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre prénom'),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Entrez votre email'],
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre email'),
                ],
            ])
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
                ],
            ]);
    }
                
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
