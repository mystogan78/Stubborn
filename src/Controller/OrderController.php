<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository; // 👈 IMPORTANT
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class OrderController extends AbstractController
{
    #[Route('/mes-commandes', name: 'app_orders', methods: ['GET'])]
    public function index(OrderRepository $orders): Response
    {
        $user = $this->getUser();
        $list = $orders->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('orders/index.html.twig', [
            'orders' => $list,
        ]);
    }

    #[Route('/mes-commandes/{id}', name: 'app_order_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Order $order): Response
    {
        // sécurité: un utilisateur ne peut voir que ses propres commandes
        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('orders/show.html.twig', [
            'order' => $order,
        ]);
    }
}
