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

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['help' => "Votre nom"])
            ->add('surname', TextType::class, ['help' => "Votre prénom"])
            ->add('username', TextType::class, ['help' => "Votre pseudonyme"])
            ->add('mail', EmailType::class, [ 'help' => "Votre adresse mail" ])
            ->add('public_name', CheckboxType::class, [
                'mapped' => false,
                'label' => false
            ])
            ->add('public_surname', CheckboxType::class, [
                'mapped' => false,
                'label' => false
            ])
            ->add('public_username', CheckboxType::class, [
                'mapped' => false,
                'label' => false
            ])
            ->add('public_mail', CheckboxType::class, [
                'mapped' => false,
                'label' => false
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