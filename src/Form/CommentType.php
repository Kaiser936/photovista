<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('text',TextareaType::class,[
            'label' => 'Commentaire',
            'attr' => [
                'placeholder' => 'Votre commentaire'
            ],
        ])
        ->add('sender',TextType::class,[
            'label' => 'Votre pseudo',
            'attr' => [
                'placeholder' => 'Votre pseudo'
            ],
        ])
        ->add('submit',SubmitType::class,[
            'label' => 'Envoyer',
            'attr' => [
                'class' => 'btn btn-primary'
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
