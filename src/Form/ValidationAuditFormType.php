<?php

namespace App\Form;

use App\Entity\Audit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidationAuditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ecart_constatation', TextareaType::class, [
                'label' => 'Ecarts et constatations'
            ])
            ->add('recommandations', TextareaType::class, [
                'label' => 'Recommandations'
            ])
            ->add('commentaire_client', TextareaType::class, [
                'label' => 'Commentaire du client'
            ])
            ->add('remarque_generale', TextareaType::class, [
                'label' => 'Remarque générale'
            ])
            ->add('valider', SubmitType::class, [
                "attr"=>["value"=>"Valider Audit",
                "class"=>"is-info is-medium is-4"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Audit::class,
            'attr' => [
                'novalidate' => 'novalidate',
                ] // Désactive la validation HTML5
        ]);
    }
}
