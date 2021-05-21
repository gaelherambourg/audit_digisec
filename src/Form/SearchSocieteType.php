<?php

namespace App\Form;

use App\Entity\Societe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchSocieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('recherche', SearchType::class, [
            'label' => false,
            'attr' => array(
                'placeholder' => 'Le nom contient'
            ),
            'mapped'=> false,
            'required'=> false
        ])
        ->add('rechercher', SubmitType::class, [
            'attr' =>[
                'class' => 'button is-info']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        
    }
}
