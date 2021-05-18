<?php

namespace App\Form;

use App\Entity\Societe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocieteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom : ',
            ])
            ->add('raison_social', TextType::class, [
                'label' => 'Raison sociale : '
            ])
            ->add('type_entreprise', TextType::class, [
                'label' => 'Type entreprise : '
            ])
            ->add('capital_social', TextType::class, [
                'label' => 'Capital social : '
            ])
            ->add('immat_rcs', TextType::class, [
                'label' => 'Immatriculation RCS : '
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo : ',
            ])
            // Insetion du formulaire AdressFormType
            ->add('adresse', AdresseFormType::class)
            // Insertion du formulaire ContactFormType
            ->add('contact', ContactFormType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Societe::class,
            'attr' => [
                'novalidate' => 'novalidate', // Désactive la validation HTML5
        ]]);
    }
}