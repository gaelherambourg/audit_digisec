<?php

namespace App\Form;

use App\Entity\Audit;
use App\Entity\EchelleNotation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('objectif_perimetre')
            ->add('role_responsabilite')
            ->add('contraintes')
            ->add('echelle_notation', EntityType::class, [
                "label"=> "Echelle de notation :",
                "class"=>EchelleNotation::class,
                "choice_label"=>"echelle"
            ])
            ->add('lancer_audit', SubmitType::class, [
                "attr"=>["value"=>"Lancer Audit",
                "class"=>"is-info is-medium"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Audit::class,
            'attr' => [
                'novalidate' => 'novalidate', // Désactive la validation HTML5
        ]]);
    }
}
