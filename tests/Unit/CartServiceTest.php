<?php
declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Product;
use App\Port\ProductLookup;
use App\Service\CartService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

final class FakeProductLookup implements ProductLookup
{
    /** @var array<int, Product> */
    private array $map = [];

    public function with(int $id, Product $product): self
    {
        $this->map[$id] = $product;
        return $this;
    }

    public function find(int $id): ?Product
    {
        return $this->map[$id] ?? null;
    }
}

final class CartServiceTest extends TestCase
{
    private CartService $cart;
    private FakeProductLookup $repo;

    protected function setUp(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $session->start();

        $request = new Request();
        $request->setSession($session);

        $stack = new RequestStack();
        $stack->push($request);

        $this->repo = new FakeProductLookup();
        $this->cart = new CartService($stack, $this->repo);
    }

    public function test_add_single_item_updates_total(): void
    {
        $p = new Product();
        $p->setName('Blackbelt');
        $p->setPrice(2990);

        $this->repo->with(1, $p);

        $this->cart->add(1, 1);

        $this->assertSame(2990, $this->cart->getTotal());
        $this->assertCount(1, $this->cart->getItems());
    }
}
