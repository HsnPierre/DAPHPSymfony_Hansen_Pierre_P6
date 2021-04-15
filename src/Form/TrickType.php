<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Grab' => 'Grab',
                    'Rotation' => 'Rotation',
                    'Rotation désaxée' => 'Rotation désaxée',
                    'Flip' => 'Flip',
                    'Slide' => 'Slide',
                    'One Foot' => 'One Foot'
                ],
            ])
            ->add('medias', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('name', TextType::class, ['help' => "Le nom de la figure"])
            ->add('mainpic', FileType::class, [
                'label' => false,
                'mapped' => false,  
            ])
            ->add('content', TextareaType::class, ['help' => "Une description de la figure et/ou comment la faire"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'date_class' => User::class,
        ]);
    }
}