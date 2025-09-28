<?php
namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_root')]
    #[Route('/home', name: 'app_home')]
    public function index(ProductRepository $repo): Response
    {
        // Les 8 derniers produits “featured”
        $featured = $repo->findBy(['featured' => true], ['id' => 'DESC'], 8);

        return $this->render('home/index.html.twig', [
            'featured' => $featured,
        ]);
    }
}
