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

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['help' => "Votre nom"])
            ->add('surname', null, ['help' => "Votre prénom"])
            ->add('username', null, ['help' => "Votre pseudonyme"])
            ->add('mail', EmailType::class, ['help' => "Votre adresse mail"])
            ->add('password', PasswordType::class, ['help' => "Votre mot de passe"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'date_class' => User::class,
        ]);
    }
}