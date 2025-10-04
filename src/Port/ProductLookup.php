<?php
declare(strict_types=1);

namespace App\Port;

use App\Entity\Product;

interface ProductLookup
{
    public function find(int $id): ?Product;
}
