<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Choisissez un email',
            'constraints' => [
                new NotBlank([
                    'message' =>'Ce champ ne peut etre vide : {{ value }}'
                ]),
                new Length([
                    'min' => 4,
                    'max' => 255,
                    'minMessage' =>'Votre email doit comporter au minimum {{ limit }} caractères.(email : {{ value }})',
                    'maxMessage' =>'Votre email doit comporter au maximum {{ limit }} caractères.(email : {{ value }})',
                ])
            ]
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' =>"Les champs du mot de passe ne sont pas identiques",
            'required' => true,
            'first_options' => ['label' =>'Mot de passe' ],
            'second_options' => ['label' => 'Répéter le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Regex([
                        'pattern' => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/",
                        'message' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule et un chiffre.',
                    ]),
                    new Length([
                        'min' => 12,
                        'max' => 255,
                        'minMessage' =>'Votre mot de passe doit comporter au minimum {{ limit }} caractères.(Mot de passe : {{ value }})',
                        'maxMessage' =>'Votre mot de passe doit comporter au maximum {{ limit }} caractères.(Mot de passe : {{ value }})',
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
            ->add('socialreason', ChoiceType::class, [
                'label' => "Vous êtes :",
                'choices' => [
                    'Particulier' => 'particulier',
                    'Entreprise' => 'entreprise',
                ],
                'expanded' => true,
                'label_attr' => [
                    'class' => 'radio-inline',
                    'for' => 'particulier-option' // ID de l'input "Particulier"
                ],
                'choice_attr' => [
                    'class' => 'radio-inline',
                    'Particulier'=>[
                        'id'=>"particulier-option"
                    ],
                    'Entreprise'=>[
                        'id'=>"particulier-option"
                    ]
                ],
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                ]
            ])            
            ->add('city', TextType::class, [
                'label'=> "Ville",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ])]
                
            ])
            ->add('company',TextType::class,[
                'label' => "Nom de l'entreprise",
                'attr' =>[
                    'id' => "company-input"
                ],
                // 'required' => false,
                // 'constraints' => [
                //     new NotBlank([
                //         'message' =>'Ce champ ne peut etre vide'
                //     ]),
                //     new Length([
                //         'min' => 1,
                //         'max' => 100,
                //         'minMessage' =>'Le nom de votre entreprise doit comporter au minimum {{ limit }} caractères.(nom : {{ value }})',
                //         'maxMessage' =>'Le nom de votre entreprise doit comporter au maximum {{ limit }} caractères.(nom : {{ value }})',
                //     ]),
                // ]
            ])
            ->add('number', TelType::class, [
                'label' => 'Numéro de téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Length([
                        'min' => 9,
                        'max' => 50,
                        'minMessage' =>'Votre numéro de téléphone doit comporter au minimum {{ limit }} caractères.(numéro de téléphone : {{ value }})',
                        'maxMessage' =>'Votre numéro de téléphone doit comporter au maximum {{ limit }} caractères.(numéro de téléphone : {{ value }})',
                    ]),
                ],
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
            ->add('submit', SubmitType::class, [
                'label'=> 'Valider',
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto col-3 btn btn-success'
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
