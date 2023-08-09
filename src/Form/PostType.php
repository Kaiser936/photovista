<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'Titre de la photo',
            'attr' => [
                'class' => 'form-control',
            ],
            ])
            ->add('description',TextareaType::class,[
                'label' => 'Description de la photo',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('picture',FileType::class,[
                'label' => 'Votre photo',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit',SubmitType::class,[
                'label' => 'Publier',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
