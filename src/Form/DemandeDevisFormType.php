<?php

namespace App\Form;

use App\Entity\DemandeDevis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DemandeDevisFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prestation', ChoiceType::class, [
                'label'=> "Prestations",
                'choices'=>[
                    'Entretien des locaux'=>"Entretien des locaux",
                    'Nettoyage des sols'=>"Nettoyage des sols",
                    'Surface vitrées et façades'=>"Surface vitrées et façades",
                    'Hygiène'=>"Hygiène",
                    'Espaces verts'=>"Espaces verts",
                    'Autre'=>'Autre'
                ],
                'placeholder' => 'Choisissez une prestation',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description du projet",
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 4,
                        'max' => 600
                    ])
                ]
            ])
            ->add('budget', MoneyType::class, [
                'label'=>"Buget estimé",
                'currency' => 'EUR',
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 5,
                        'minMessage' =>'Ce champ doit comporter au minimum {{ limit }} caractères.',
                        'maxMessage' =>'Ce champ doit comporter au maximum {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => '€'
                ]
            ])
            ->add('date', DateType::class, [
                'label'=>"Date d'intervention souhaitée",
                'widget'=>'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider",
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto my-3 col-4 btn btn-warning'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeDevis::class,
        ]);
    }
}
