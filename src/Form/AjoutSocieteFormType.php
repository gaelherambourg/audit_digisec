<?php

namespace App\Form;

use App\Form\AdresseFormType;
use App\Form\ContactFormType;
use App\Form\SocieteFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjoutSocieteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('societe', SocieteFormType::class)
            // Insetion du formulaire AdressFormType
            ->add('adresse', AdresseFormType::class)
            // Insertion du formulaire ContactFormType
            ->add('contact', ContactFormType::class)
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
