<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'placeholder' => 'Entrez votre nom',
                'class' => 'form-control form-control mb-3'
            ]
        ])
        ->add('email', TextType::class, [
            'label' => 'Email',
            'attr' => [
                'placeholder' => 'Entrez votre email',
                'class' => 'form-control form-control mb-3'
            ]
        ])
        ->add('message', TextareaType::class, [
            'label' => 'Message',
            'attr' => [
                'placeholder' => 'Votre message svp !',
                'class' => 'form-control form-control mb-3'
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Envoyer',
            'attr' => [
                'class' => 'form-control form-control mb-3'
            ]
        ]);
    }
}