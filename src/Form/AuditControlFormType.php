<?php

namespace App\Form;

use App\Entity\AuditControle;
use App\Entity\PointControle;
use App\Entity\Preuve;
use App\Entity\Remediation;
use App\Entity\RemediationControle;
use App\Repository\PointControleRepository;
use App\Repository\RemediationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuditControlFormType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('id', HiddenType::class, [
                "mapped"=>false
            ])
            ->add('remarque')
            ->add('note')
            /*  ->add('remediations', EntityType::class,
            [
                'class' => Remediation::class,
                'query_builder' => function (RemediationRepository $er) use ($options){
                    return $er->createQueryBuilder('r')
                        ->andWhere('r.pointControle = :val')
                        ->setParameter('val', 114 )
                        ->orderBy('r.libelle', 'ASC');
                },
                'choice_label' => 'libelle',
                'mapped' => false,
                'expanded' => true,
                'multiple' => true
            ])*/
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AuditControle::class,
        ]);
    }
}
