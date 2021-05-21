<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_contact', TextType::class, [
                'label' => 'Nom : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne doit pas être vide'
                    ]),
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'Ce champ a un maximum de 30 caractères'
                    ])
                ]
            ])
            ->add('prenom_contact', TextType::class, [
                'label' => 'Prénom : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne doit pas être vide'
                    ]),
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'Ce champ a un maximum de 30 caractères.'
                    ])
                ]
            ])
            ->add('tel_contact', TextType::class, [
                'label' => 'Téléphone : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne doit pas être vide'
                    ]),
                    new Regex([
                        'pattern' => '/^(\+[0-9]{2}[.\-\s]?|00[.\-\s]?[0-9]{2}|0)([0-9]{1,3}[.\-\s]?(?:[0-9]{2}[.\-\s]?){4})$/',
                        'message' => 'Merci de renseigner un numéro de téléphone valide.'
                    ]),
                    new Length([
                        'max' => 20,
                        'maxMessage' => 'Ce champs a un maximu de 20 caractères.'
                    ])
                ]
            ])
            ->add('email_contact', EmailType::class, [
                'label' => 'Mail : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne doit pas être vide.'
                    ]),
                    new Email([
                        'message' => 'Merci de renseigner un mail valide.'
                    ]),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Ce champs a un maximum de 50 caractères.'
                    ])
                ]
            ])
            ->add('poste_contact', TextType::class, [
                'label' => 'Poste : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne doit pas être vide'
                    ]),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'Ce champs a un maximum de 100 caractères.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
