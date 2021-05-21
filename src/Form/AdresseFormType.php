<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class AdresseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libellé : '
            ])
            ->add('rue', TextType::class, [
                'label' => 'Rue : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne doit pas être vide'
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Ce champs a un maximum de 255 caractères.'
                    ])
                ]
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code postal : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne doit pas être vide.'
                    ]),
                    new Regex([
                        'pattern' => '/^(?:[0-8]\d|9[0-8])\d{3}$/',
                        'message' => 'Merci de renseigner un code postal valide.'
                    ]),
                    new Length([
                        'max' => '10',
                        'maxMessage' => 'Ce champs un maximum de 10 caractères.'
                    ])
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne doit pas être vide.'
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
            'data_class' => Adresse::class,
        ]);
    }
}
