<?php
declare(strict_types=1);

namespace App\Service;

use App\Port\ProductLookup;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class CartService
{
    private const SESSION_KEY = 'cart';

    public function __construct(
        private RequestStack $requestStack,
        private ProductLookup $products
    ) {}

    private function session(): SessionInterface
    {
        $session = $this->requestStack->getSession();
        if (!$session) {
            throw new \LogicException('Aucune session disponible (pas de requête courante).');
        }
        return $session;
    }

    public function add(int $productId, int $qty = 1): void
    {
        if ($qty <= 0) return;
        $cart = $this->getRaw();
        $cart[$productId] = ($cart[$productId] ?? 0) + $qty;
        $this->session()->set(self::SESSION_KEY, $cart);
    }

    public function changeQty(int $productId, int $qty): void
    {
        $cart = $this->getRaw();
        if ($qty <= 0) unset($cart[$productId]); else $cart[$productId] = $qty;
        $this->session()->set(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->getRaw();
        unset($cart[$productId]);
        $this->session()->set(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        $this->session()->set(self::SESSION_KEY, []);
    }

    public function getRaw(): array
    {
        return $this->session()->get(self::SESSION_KEY, []);
    }

    public function getItems(): array
    {
        $items = [];
        foreach ($this->getRaw() as $productId => $qty) {
            if ($qty <= 0) continue;
            $p = $this->products->find($productId);
            if (!$p) continue;

            $price = (int) $p->getPrice();
            $items[] = [
                'id'        => $productId,
                'name'      => (string) $p->getName(),
                'price'     => $price,
                'qty'       => (int) $qty,
                'lineTotal' => $price * (int) $qty,
            ];
        }
        return $items;
    }

    public function getTotal(): int
    {
        $sum = 0;
        foreach ($this->getItems() as $i) $sum += $i['lineTotal'];
        return $sum;
    }
}
