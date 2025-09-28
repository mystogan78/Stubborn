<?php
namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(Request $request, ProductRepository $repo): Response
    {
        $range = $request->query->get('range', 'all');

        // fourchettes en EUROS (affichage)
        $map = [
            '10-29' => [10.0, 29.0],
            '29-35' => [29.0, 35.0],
            '35-50' => [35.0, 50.0],
        ];

        $products = [];

        if (isset($map[$range])) {
            [$minEuro, $maxEuro] = $map[$range];

            // Interprétation UX: "10–29 €" => de 10,00 € à 29,99 €
            $minCents = (int) round($minEuro * 100);
            $maxCents = (int) round($maxEuro * 100) + 99; // inclut .99

            // Variante propre (bornes non chevauchantes) :
            // $maxExclusiveCents = (int) round(($maxEuro + 1) * 100);
            // repo: price >= min AND price < maxExclusive

            $products = $repo->findByPriceRangeCents($minCents, $maxCents);
        } else {
            $products = $repo->findBy([], ['price' => 'ASC']);
        }

        return $this->render('products/index.html.twig', [
            'products' => $products,
            'range'    => $range,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show', requirements: ['id' => '\d+'])]
    public function show(Product $product): Response
    {
        
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }
}
