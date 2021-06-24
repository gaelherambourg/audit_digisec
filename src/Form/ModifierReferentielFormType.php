<?php

namespace App\Form;

use App\Form\ChapitreFormType;
use App\Form\ReferentielFormType;
use App\Form\PointControleFormType;
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
        ->add('chapitre', ChapitreFormType::class)
        ->add('recommandation',RecommandationFormType::class)
        ->add('pointControle', CollectionType::class, [
            'entry_type' => PointControleFormType::class,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'novalidate' => 'novalidate', // Désactive la validation HTML5
        ]]);
    }
}
