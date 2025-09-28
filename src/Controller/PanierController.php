<?php
// src/Controller/PanierController.php
namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier')]
final class PanierController extends AbstractController
{
    #[Route('', name: 'app_panier_show', methods: ['GET'])]
    public function show(Request $request, ProductRepository $repo): Response
    {
        $cart = $request->getSession()->get('cart', []); // [productId => qty]

        $items = [];
        $total = 0.0; // total en EUROS pour l'affichage

        foreach ($cart as $id => $qty) {
            $product = $repo->find($id);
            if (!$product) { continue; }

            $qty = max(1, (int) $qty);

            // ⚠️ Product::getPrice() retourne des CENTIMES (int)
            $unitCents = (int) $product->getPrice();
            $lineCents = $unitCents * $qty;

            // On pousse en euros pour ton Twig actuel (items/total)
            $items[] = [
                'product'   => $product,
                'qty'       => $qty,
                'lineTotal' => $lineCents / 100, // euros (float)
            ];
            $total += $lineCents / 100; // euros
        }

        return $this->render('panier/index.html.twig', [
            'items' => $items,  // attendu par ton template
            'total' => $total,  // en euros
        ]);
    }

    #[Route('/ajouter/{id}', name: 'app_panier_add', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function add(Product $product, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('add_to_cart'.$product->getId(), (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $qty = max(1, (int) $request->request->get('qty', 1));

        $session = $request->getSession();
        $cart = $session->get('cart', []);
        $cart[$product->getId()] = ($cart[$product->getId()] ?? 0) + $qty;
        $session->set('cart', $cart);

        $this->addFlash('success', sprintf('%s ×%d ajouté au panier.', $product->getName(), $qty));
        return $this->redirect($request->headers->get('referer') ?: $this->generateUrl('app_panier_show'));
    }

    #[Route('/diminuer/{id}', name: 'app_panier_decrement', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function decrement(Product $product, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('decrement_from_cart'.$product->getId(), (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $session = $request->getSession();
        $cart = $session->get('cart', []);
        $id = $product->getId();

        if (isset($cart[$id])) {
            $cart[$id]--;
            if ($cart[$id] <= 0) {
                unset($cart[$id]);
            }
            $session->set('cart', $cart);
        }

        return $this->redirectToRoute('app_panier_show');
    }

    #[Route('/supprimer/{id}', name: 'app_panier_remove', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function remove(Product $product, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('remove_from_cart'.$product->getId(), (string) $request->request->get('_token'))) {
            $this->addFlash('danger', 'Action non autorisée (CSRF).');
            return $this->redirectToRoute('app_panier_show');
        }

        $session = $request->getSession();
        $cart = $session->get('cart', []);
        unset($cart[$product->getId()]);
        $session->set('cart', $cart);

        $this->addFlash('success', 'Produit retiré du panier.');
        return $this->redirectToRoute('app_panier_show');
    }

    #[Route('/vider', name: 'app_panier_clear', methods: ['POST'])]
    public function clear(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('clear_cart', (string) $request->request->get('_token'))) {
            $this->addFlash('danger', 'Action non autorisée (CSRF).');
            return $this->redirectToRoute('app_panier_show');
        }

        $request->getSession()->set('cart', []);
        $this->addFlash('success', 'Panier vidé.');
        return $this->redirectToRoute('app_panier_show');
    }

    #[Route('/commander', name: 'app_panier_checkout', methods: ['POST'])]
    public function checkout(Request $request, ProductRepository $repo, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('checkout', (string) $request->request->get('_token'))) {
            $this->addFlash('danger', 'Action non autorisée (CSRF).');
            return $this->redirectToRoute('app_panier_show');
        }

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cart = $request->getSession()->get('cart', []);
        if (!$cart) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('app_panier_show');
        }

        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $order = (new Order())
            ->setUser($user)
            ->setStatus('new');

        $totalCents = 0;

        foreach ($cart as $productId => $qty) {
            $product = $repo->find($productId);
            if (!$product) { continue; }

            $qty = max(1, (int) $qty);

            // ⚠️ prix en centimes
            $unitCents = (int) $product->getPrice();
            $lineCents = $unitCents * $qty;

            $item = (new OrderItem())
                ->setOrderRef($order)
                ->setProduct($product)
                ->setQuantity($qty)
                ->setUnitPriceCents($unitCents)
                ->setLineTotalCents($lineCents);

            $order->addItem($item);
            $totalCents += $lineCents;
        }

        $order->setTotalCents($totalCents);

        $em->persist($order);
        $em->flush();

        $request->getSession()->set('cart', []);
        $this->addFlash('success', 'Commande créée !');
        return $this->redirectToRoute('app_home');
    }
}
