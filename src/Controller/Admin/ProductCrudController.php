<?php
namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setPageTitle(Crud::PAGE_INDEX, 'Produits')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un produit')
            ->setPageTitle(Crud::PAGE_EDIT, fn(Product $p) => sprintf('Modifier %s', $p->getName()));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield TextField::new('name', 'Nom');

        yield TextareaField::new('description')->hideOnIndex();

        // Prix stocké en CENTIMES (int)
        yield MoneyField::new('price', 'Prix')
            ->setCurrency('EUR')
            ->setStoredAsCents(true);

        yield IntegerField::new('stock', 'Stock')
            ->formatValue(fn($value) => $value ?? 0)
            ->setRequired(false);

        // Image (affichage + upload)
        yield ImageField::new('imageUrl', 'Photo')               // ← correction ici
            ->setBasePath('images/products')                     // URL publique (pour l'index/détail)
            ->setUploadDir('public/images/products')             // dossier physique
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->setRequired(false);
    }
}
