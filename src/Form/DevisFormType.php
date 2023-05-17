<?php

namespace App\Form;

use App\Entity\Devis;
use App\Form\DetailDevisFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DevisFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('client', TextType::class, [
            'label' => 'Client',
            'constraints' => [
                new NotBlank([
                    'message' =>'Ce champ ne peut etre vide'
                ]),
                new Length([
                    'min' => 1,
                    'max' => 100,
                    'minMessage' =>'Le nom client doit comporter au minimum {{ limit }} caractères.(nom : {{ value }})',
                    'maxMessage' =>'Le nom client doit comporter au maximum {{ limit }} caractères.(nom : {{ value }})',
                ]),
            ],
        ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 100,
                        'minMessage' =>'Votre nom doit comporter au minimum {{ limit }} caractères.(nom : {{ value }})',
                        'maxMessage' =>'Votre nom doit comporter au maximum {{ limit }} caractères.(nom : {{ value }})',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' =>'Votre prénom doit comporter au minimum {{ limit }} caractères.(prénom : {{ value }})',
                        'maxMessage' =>'Votre prénom doit comporter au maximum {{ limit }} caractères.(prénom : {{ value }})',
                    ]),
                ],
            ])
            ->add('society', TextType::class, [
                'label' => 'Nom de la société'
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 200,
                        'minMessage' =>'Votre adresse doit comporter au minimum {{ limit }} caractères. adresse : {{ value }})',
                        'maxMessage' =>'Votre adresse doit comporter au maximum {{ limit }} caractères. adresse : {{ value }})',
                    ]),
                ],
            ])
            ->add('zipcode', IntegerType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' =>'Votre code postal doit comporter au minimum {{ limit }} caractères.(code postal : {{ value }})',
                        'maxMessage' =>'Votre code postal doit comporter au maximum {{ limit }} caractères.(code postal : {{ value }})',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label'=> "Ville création devis",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ])]
                
            ])
            ->add('date', DateType::class, [
                'label'=>"Date création devis",
                'widget'=>'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ])
                ]
            ])
            ->add('intervention', TextType::class, [
                'label'=>"Lieu d'intervention"
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Suivant",
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto my-3 col-3 btn btn-primary'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
