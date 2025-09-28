<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    public const SIZES = ['XS','S','M','L','XL'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name = '';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    // Prix en centimes
    #[ORM\Column]
    private int $price = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: 'json')]
    private array $stockBySize = [
        'XS' => 2, 'S' => 2, 'M' => 2, 'L' => 2, 'XL' => 2,
    ];

    #[ORM\Column(type: 'boolean')]
    private bool $featured = false;

    #[ORM\Column(type: 'integer', options: ['default' => 0], nullable: false)]
    private int $stock = 0; // ← stock global (optionnel si tu utilises stockBySize)

    // --- Getters / Setters ---

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): self { $this->description = $d; return $this; }

    public function getPrice(): int { return $this->price; }
    public function setPrice(int $cents): self { $this->price = $cents; return $this; }

    public function getPriceEuro(): float { return $this->price / 100; }
    public function setPriceEuro(float $euros): self { $this->price = (int) round($euros * 100); return $this; }

    public function getImageUrl(): ?string { return $this->imageUrl; }
    public function setImageUrl(?string $u): self { $this->imageUrl = $u; return $this; }

    public function getStockBySize(): array { return $this->stockBySize; }
    public function setStockBySize(array $s): self { $this->stockBySize = $s; return $this; }

    public function getStockForSize(string $size): int
    {
        return $this->stockBySize[$size] ?? 0;
    }

    public function setStockForSize(string $size, int $qty): self
    {
        $this->stockBySize[$size] = max(0, $qty);
        return $this;
    }

    public function isFeatured(): bool { return $this->featured; }
    public function setFeatured(bool $f): self { $this->featured = $f; return $this; }

    // ✅ Manquants (pour EasyAdmin)
    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = max(0, $stock);
        return $this;
    }

    // (Optionnel) Helpers si tu affiches le stock total calculé
    public function getStockTotal(): int
    {
        return array_sum($this->stockBySize ?? []);
    }

    public function getStockSummary(): string
    {
        if (!$this->stockBySize) return '—';
        $parts = [];
        foreach ($this->stockBySize as $size => $qty) {
            $parts[] = sprintf('%s:%d', $size, $qty);
        }
        return implode(' | ', $parts);
    }
    public function __toString(): string
   {
    return $this->name ?: 'Produit #'.$this->id;
   }


}
