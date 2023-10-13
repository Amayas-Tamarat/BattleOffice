<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('postalCode', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'Code postal',
                    'name' => 'Code postal',
                    'required' => true,
                    ]
                ])
            ->add('ville', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'Ville',
                    'name' => 'Ville',
                    'required' => true,
                    ]
                ])
            ->add('adress', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'Adresse',
                    'name' => 'Adresse',
                    'required' => true,
                    ]
                ])
            ->add('complement', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'Complément adr.',
                    'name' => 'Complément adr.',
                    'required' => true,
                    ]
                ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_attr' => [
                    'class' => 'g-3'
                ],
                'label' => 'Pays',
                'row_attr' => [
                    'class' => 'input-field',
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
