<?php

namespace App\Form;

use App\Entity\DetailDevis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DetailDevisFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('designation', TextType::class, [
            'label' => 'Désignation',
            
        ])
            ->add('priceunit', MoneyType::class, [
                'label' => 'Prix unitaire',
                'currency' => 'EUR',
                
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité',
            ])
                ->add('Ajouter', SubmitType::class, [
                    'label' => "Envoyer",
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
            'data_class' => DetailDevis::class,
            //permet la soumission de champs supplémentaires
            'allow_extra_fields' => true,
        ]);
    }
}
