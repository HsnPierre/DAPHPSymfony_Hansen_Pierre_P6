<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['help' => "Votre nom"])
            ->add('surname', TextType::class, ['help' => "Votre prénom"])
            ->add('username', TextType::class, ['help' => "Votre pseudonyme"])
            ->add('mail', RepeatedType::class, [
                'type' => EmailType::class, 
                'invalid_message' => 'Les deux adresses doivent être identiques',
                'first_options' => ['label' => 'Adresse mail', 'help' => 'Votre adresse mail'],
                'second_options' => ['label' => "Confirmer l'adresse mail"]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identiques',
                'first_options' => ['label' => 'Mot de passe', 'help' => 'Votre mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe']
            ])
            ->add('rgpd', CheckboxType::class, [
                'label' => "J'autorise ce site à conserver mes données personnelles transmises via ce formulaire. Aucune exploitation commerciale ne sera faite des données conservées.",
                'required' => true    
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'date_class' => User::class,
        ]);
    }
}