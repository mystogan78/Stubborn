<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class ProductFixtures extends Fixture
{
    public function load(ObjectManager $om): void
    {
        
        $models = [
            ['name' => 'Blackbelt', 'price' => 29.90, 'img' => 'images/products/1.jpeg','featured' => false],
            ['name' => 'BlueBelt',  'price' => 29.90, 'img' => 'images/products/8.jpeg', 'featured' => false],
            ['name' => 'OrangeStreet', 'price' => 34.50, 'img' => 'images/products/3.jpeg', 'featured' => true],
            ['name' => 'StreetBall',  'price' => 45.00, 'img' => 'images/products/9.jpeg',  'featured' => false],
            ['name' => 'PinkLady',  'price' => 29.90, 'img' => 'images/products/5.jpeg','featured' => true],
            ['name' => 'Snow',   'price' => 29.90, 'img' => 'images/products/6.jpeg','featured' => false],
            ['name' => 'Greenback',  'price' => 34.50, 'img' => 'images/products/10.jpeg',  'featured' => true],
            ['name' => 'BlueNight', 'price' => 34.50, 'img' => 'images/products/2.jpeg','featured' => false],
        ];

        foreach ($models as $m) {
            $p = (new Product())
                ->setName($m['name'])
                ->setDescription('Sweat-shirt '.$m['name'].' – coupe confortable, toutes tailles.')
                ->setPrice((int) round($m['price'] * 100 ))               // prix en euros
                ->setImageUrl($m['img'])               // mets bien les images dans public/images/
                ->setFeatured($m['featured'])
                ->setStockBySize(['XS'=>2,'S'=>2,'M'=>2,'L'=>2,'XL'=>2]); // ≥ 2 par taille

            $om->persist($p);
        }

        $om->flush();
    }
}
