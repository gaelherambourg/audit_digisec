<?php

namespace App\Form;

use App\Entity\Audit;
use App\Entity\AuditControle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuditPointControleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('remarque', RemarqueRecommandationFormType::class)
            ->add('audit_controle', CollectionType::class, [
                'entry_type' => AuditControlFormType::class,
                "label" => false
            ])
            ->add('enregistrer', SubmitType::class, [
                "attr"=>["value"=>"Enregistrer",
                "class"=>"is-info is-medium"
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'novalidate' => 'novalidate',
                ] // Désactive la validation HTML5
        ]);
    }
}
