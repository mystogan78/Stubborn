<?php
namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CheckoutController extends AbstractController
{
    #[Route('/checkout/create', name: 'stripe_checkout_create', methods: ['POST'])]
    public function create(Request $request, ProductRepository $products, UrlGeneratorInterface $urls): JsonResponse
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $cart = $request->getSession()->get('cart', []);
        if (!$cart) {
            return new JsonResponse(['error' => 'Panier vide'], 400);
        }

        $lineItems = [];
        foreach ($cart as $productId => $qty) {
            $p = $products->find($productId);
            if (!$p) continue;

            $lineItems[] = [
                'quantity' => $qty,
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $p->getPrice(),
                    'product_data' => [
                        'name' => $p->getName(),
                    ],
                ],
            ];
        }

        $success = $urls->generate('checkout_success', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $cancel = $urls->generate('checkout_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $session = CheckoutSession::create([
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => $success,
            'cancel_url' => $cancel,
        ]);

        return new JsonResponse(['id' => $session->id]);
    }

    #[Route('/checkout/success', name: 'checkout_success', methods: ['GET'])]
    public function success(Request $request, ProductRepository $products, EntityManagerInterface $em): Response
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        if ($cart && $this->getUser()) {
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setStatus('paid');
            $total = 0;

            foreach ($cart as $productId => $qty) {
                $p = $products->find($productId);
                if (!$p) continue;

                $item = new OrderItem();
                $item->setProduct($p);
                $item->setQuantity($qty);
                $item->setUnitPriceCents($p->getPrice());
                $item->setLineTotalCents($p->getPrice() * $qty);

                $total += $p->getPrice() * $qty;
                $order->addItem($item);
                $em->persist($item);
            }

            $order->setTotalCents($total);
            $em->persist($order);
            $em->flush();

            $session->remove('cart');
        }

        return $this->render('checkout/success.html.twig');
    }

    #[Route('/checkout/cancel', name: 'checkout_cancel', methods: ['GET'])]
    public function cancel(): Response
    {
        return $this->render('checkout/cancel.html.twig');
    }
}
