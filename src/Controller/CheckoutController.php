<?php
namespace App\Controller;

use App\Repository\ProductRepository;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;

final class CheckoutController extends AbstractController
{
    #[Route('/checkout/create', name: 'stripe_checkout_create', methods: ['POST'])]
    public function create(Request $request, ProductRepository $products, UrlGeneratorInterface $urls): JsonResponse
    {
        // Clé secrète test
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        // Panier en session: [productId => qty]
        $cart = $request->getSession()->get('cart', []);
        if (!$cart) {
            return new JsonResponse(['error' => 'Panier vide'], 400);
        }

        // Construire les line_items
        $lineItems = [];
        foreach ($cart as $productId => $qty) {
            if ($qty <= 0) { continue; }
            $p = $products->find($productId);
            if (!$p) { continue; }

            $lineItems[] = [
                'quantity'   => (int) $qty,
                'price_data' => [
                    'currency'     => 'eur',
                    'unit_amount'  => $p->getPrice(), // en centimes
                    'product_data' => ['name' => $p->getName()],
                ],
            ];
        }

        if (!$lineItems) {
            return new JsonResponse(['error' => 'Aucun article valide'], 400);
        }

        // URLs absolues
        $success = $urls->generate('checkout_success', [], UrlGeneratorInterface::ABSOLUTE_URL) . '?session_id={CHECKOUT_SESSION_ID}';
        $cancel  = $urls->generate('checkout_cancel',  [], UrlGeneratorInterface::ABSOLUTE_URL);

        // Créer la session Checkout
        $session = CheckoutSession::create([
            'mode'        => 'payment',
            'line_items'  => $lineItems,
            'success_url' => $success,
            'cancel_url'  => $cancel,
            // 'customer_email' => $this->getUser()?->getEmail(), // optionnel
        ]);

        return new JsonResponse(['id' => $session->id]);
    }

    #[Route('/checkout/success', name: 'checkout_success', methods: ['GET'])]
    public function success(Request $request): Response
    {
        $request->getSession()->remove('cart');
        return $this->render('checkout/success.html.twig');
    }

    #[Route('/checkout/cancel', name: 'checkout_cancel', methods: ['GET'])]
    public function cancel(): Response
    {
        return $this->render('checkout/cancel.html.twig');
    }
}
