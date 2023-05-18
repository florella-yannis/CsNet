<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('currentpassword', PasswordType::class, [
            'label'=>"Mot de passe actuel",
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir votre mot de passe actuel.',
                ]),
                // Ajouter des contraintes pour valider le mot de passe actuel
            ],
        ])
        ->add('newpassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe ne sont pas identiques.',
            'required' => true,
            'first_options'  => ['label' => 'Nouveau mot de passe'],
            'second_options' => ['label' => 'Confirmer le nouveau mot de passe'],
            'constraints' => [
                new NotBlank([
                    'message' =>'Ce champ ne peut etre vide'
                ]),
                new Length([
                    'min' => 4,
                    'max' => 255,
                    'minMessage' =>'Votre mot de passe doit comporter au minimum {{ limit }} caractères.(Mot de passe : {{ value }})',
                    'maxMessage' =>'Votre mot de passe doit comporter au maximum {{ limit }} caractères.(Mot de passe : {{ value }})',
                ]),
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label'=> 'Valider',
            'validate' => false,
            'attr' => [
                'class' => 'd-block mx-auto col-3 btn btn-warning'
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
