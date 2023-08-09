<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class InscriptionType extends AbstractType
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Adresse email',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('password', PasswordType::class, [
            'label' => 'Mot de passe',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('pseudo', TextType::class, [
            'label' => 'Pseudo',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('biographie', TextareaType::class, [
            'label' => 'Biographie',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Inscription',
            'attr' => [
                'class' => 'form-control',
            ],
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
