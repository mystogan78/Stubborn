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
            ['name' => 'Blackbelt', 'price' => 29.90, 'img' => 'images/products/Sweat_black.png','featured' => false],
            ['name' => 'BlueBelt',  'price' => 29.90, 'img' => 'images/products/Sweat_blue.png', 'featured' => false],
            ['name' => 'Street',    'price' => 34.50, 'img' => 'images/products/Sweat_orange.png', 'featured' => true],
            ['name' => 'redball',  'price' => 45.00, 'img' => 'images/products/Sweat_red.png',  'featured' => false],
            ['name' => 'PinkLady',  'price' => 29.90, 'img' => 'images/products/Sweat_pink.png','featured' => true],
            ['name' => 'Snow',      'price' => 29.90, 'img' => 'images/products/Sweat_white.png','featured' => false],
            ['name' => 'Greenback',  'price' => 34.50, 'img' => 'images/products/Sweat_green.png',  'featured' => true],
            ['name' => 'BlueCloud', 'price' => 34.50, 'img' => 'images/products/Sweat_blue2.png','featured' => false],
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
