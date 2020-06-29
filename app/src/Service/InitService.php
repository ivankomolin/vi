<?php

namespace App\Service;

use Faker\Generator;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use App\Repository\ProductRepository;

class InitService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @param EntityManager $entityManager
     * @param Generator $faker
     * @param ProductRepository $productRepository
     */
    public function __construct(
        EntityManager $entityManager,
        Generator $faker,
        ProductRepository $productRepository
    ) {
        $this->entityManager = $entityManager;
        $this->faker = $faker;
        $this->productRepository = $productRepository;
    }

    /**
     * Генерирует стартовый набор данных и выводит их список
     * Если набор данных уже существует, выводит список
     *
     * @param int $total
     *
     * @return Product[]
     */
    public function generateFakeProducts(int $total): array
    {
        $products = $this->productRepository->findAll();
        if (empty($products)) {
            for ($i = 0; $i <= $total; $i++) {
                $product = new Product;
                $product->name = $this->faker->sentence();
                $product->price = $this->faker
                    ->randomFloat($product::PRICE_SCALE, 100, 9999);
                $this->entityManager->persist($product);
            }

            $this->entityManager->flush();
            $this->entityManager->clear();

            $products = $this->productRepository->findAll();
        }

        return $products;
    }
}
