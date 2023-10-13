<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'attr' =>[
                    'class' => 'form-control',
                    'id' => 'Name',
                    'name' => 'Name',
                    'required' => true,
                ]
            ])
            ->add('last_name', TextType::class,[
                'attr' =>[
                    'class' => 'form-control',
                    'id' => 'Last name',
                    'name' => 'Last name',
                    'required' => true,
                ]
            ])
            ->add('phone', TelType::class,[
                'attr' =>[
                    'class' => 'form-control',
                    'id' => 'Phone',
                    'name' => 'Phone',
                    'required' => true,
                ]
            ])
            ->add('mail', EmailType::class,[
                'attr' =>[
                    'class' => 'form-control',
                    'id' => 'Mail',
                    'name' => 'Mail',
                    'required' => false
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
