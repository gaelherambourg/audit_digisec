<?php

namespace App\Form;

use App\Entity\Preuve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class PreuveFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('preuve', ChoiceType::class, [
                'choices'=>
                    [
                        'Texte' =>0,
                        'Fichier'=>1,
                        'Image'=>2

                    ],
                'mapped'=> false,
                'label' => 'Type de preuve : '
            ])
            ->add('texte', TextareaType::class, [
                'label' => 'Texte : '
            ])
            ->add('fichier', FileType::class, [
                'label' => 'Fichier : ',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'maxSizeMessage' => 'La taille max est de 5 mo'
                    ])
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Image : ',
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                        'maxSizeMessage' => 'La taille max est de 5 mo'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Preuve::class,
            'attr' => [
                'novalidate' => 'novalidate', // Désactive la validation HTML5
        ]]);
    }
}
