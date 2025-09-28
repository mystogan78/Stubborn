<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Inversé avec Order::items
    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $orderRef = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private int $quantity = 1;

    #[ORM\Column]
    private int $unitPriceCents = 0;

    #[ORM\Column]
    private int $lineTotalCents = 0;

    public function getId(): ?int { return $this->id; }

    public function getOrderRef(): ?Order { return $this->orderRef; }
    public function setOrderRef(?Order $order): self { $this->orderRef = $order; return $this; }

    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(Product $p): self { $this->product = $p; return $this; }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $q): self { $this->quantity = max(1, $q); return $this; }

    public function getUnitPriceCents(): int { return $this->unitPriceCents; }
    public function setUnitPriceCents(int $c): self { $this->unitPriceCents = max(0, $c); return $this; }

    public function getLineTotalCents(): int { return $this->lineTotalCents; }
    public function setLineTotalCents(int $c): self { $this->lineTotalCents = max(0, $c); return $this; }
}
