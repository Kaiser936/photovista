<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextField::new('description'),
            DateField::new('createdAt'),
            DateField::new('updatedAt'),    
            ImageField::new('picture')
            ->setUploadDir('uploads') // Utilisez le chemin relatif à partir du répertoire "public"
            ->setBasePath('uploads') // Utilisez également le chemin relatif pour le répertoire de base
            ->setLabel('Picture') // Libellé du champ
            // Autres options du champ
        
        
        ];
    }
}
