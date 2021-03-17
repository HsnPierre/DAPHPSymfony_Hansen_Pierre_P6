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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['help' => "Votre nom"])
            ->add('surname', null, ['help' => "Votre prénom"])
            ->add('username', null, ['help' => "Votre pseudonyme"])
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'date_class' => User::class,
        ]);
    }
}