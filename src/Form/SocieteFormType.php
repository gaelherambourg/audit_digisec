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
            ->add('rue', TextType::class, [
                'label' => 'Rue : ',
                'mapped' => false
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code postal : ',
                'mapped' => false
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville : ',
                'mapped' => false
            ])
            ->add('nom_contact', TextType::class, [
                'label' => 'Nom : ',
                'mapped' => false
            ])
            ->add('tel_contact', TextType::class, [
                'label' => 'Téléphone : ',
                'mapped' => false
            ])
            ->add('mail_contact', TextType::class, [
                'label' => 'Mail : ',
                'mapped' => false
            ])
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