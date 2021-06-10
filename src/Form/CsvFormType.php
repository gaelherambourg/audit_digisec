<?php

namespace App\Form;

use App\Model\CsvForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CsvFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('referentielCsv', FileType::class, [
                'label' => 'Référentiel : ',
            ])
            ->add('chapitreCsv', FileType::class, [
                'label' => 'Chapitres : ',
            ])
            ->add('recommandationCsv', FileType::class, [
                'label' => 'Recommendations : ',
            ])
            ->add('typePreuveCsv', FileType::class, [
                'label' => 'Preuves : ',
            ])
            ->add('pointControleCsv', FileType::class, [
                'label' => 'Points de contrôle : ',
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CsvForm::class,
        ]);
    }
}
