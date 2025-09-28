<?php

// src/Repository/ProductRepository.php
namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
final class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /** @return Product[] */
   public function findByPriceRangeCents(int $minCents, int $maxCents): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.price >= :min')
            ->andWhere('p.price <= :max')
            ->setParameter('min', $minCents)
            ->setParameter('max', $maxCents)
            ->orderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

}