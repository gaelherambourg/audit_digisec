<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_contact', TextType::class, [
                'label' => 'Nom : ',
            ])
            ->add('prenom_contact', TextType::class, [
                'label' => 'Prénom : ',
            ])
            ->add('tel_contact', TextType::class, [
                'label' => 'Téléphone : '
            ])
            ->add('email_contact', EmailType::class, [
                'label' => 'Mail : '
            ])
            ->add('poste_contact', TextType::class, [
                'label' => 'Poste : '
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
