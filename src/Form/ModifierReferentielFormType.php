<?php

namespace App\Form;

use App\Form\ChapitreFormType;
use App\Form\ReferentielFormType;
use App\Form\RecommandationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ModifierReferentielFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('referentiel', ReferentielFormType::class)
        ->add('chapitre', CollectionType::class, [
            'entry_type' => ChapitreFormType::class,
        ])
        ->add('recommandation', CollectionType::class, [
            'entry_type' => RecommandationFormType::class,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
