<?php

namespace App\Form;

use App\Entity\Prospect;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ProspectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label'=>"Prénom",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 50,
                        'minMessage' =>'Ce champ doit comporter au minimum {{ limit }} caractères.',
                        'maxMessage' =>'Ce champ doit comporter au maximum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => "Nom",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 50,
                        'minMessage' =>'Ce champ doit comporter au minimum {{ limit }} caractères.',
                        'maxMessage' =>'Ce champ doit comporter au maximum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('subject', TextType::class, [
                'label'=>"Sujet",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 255,
                        'minMessage' =>'Ce champ doit comporter au minimum {{ limit }} caractères.',
                        'maxMessage' =>'Ce champ doit comporter au maximum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => "Email",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 50,
                        'minMessage' =>'Ce champ doit comporter au minimum {{ limit }} caractères.',
                        'maxMessage' =>'Ce champ doit comporter au maximum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('number', NumberType::class, [
                'label' => "Numéro de téléphone",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => "Message",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 600,
                        'minMessage' =>'Ce champ doit comporter au minimum {{ limit }} caractères.',
                        'maxMessage' =>'Ce champ doit comporter au maximum {{ limit }} caractères.',
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Envoyer",
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto my-3 col-3 btn btn-primary'
                ]
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
                'locale'=>'fr'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class,
        ]);
    }
}
