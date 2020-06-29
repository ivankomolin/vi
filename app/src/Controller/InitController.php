<?php

namespace App\Controller;

use App\Service\InitService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\DTO;

class InitController
{
    /**
     * Количество товаров, генерируемое при инициализации
     */
    const TOTAL_FAKE_PRODUCTS = 20;

    /**
     * @var InitService
     */
    private $initService;

    /**
     * @param InitService $initService
     */
    public function __construct(InitService $initService)
    {
        $this->initService = $initService;
    }

    /**
     * Генерация стартового набора данных
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = $this->initService
            ->generateFakeProducts(self::TOTAL_FAKE_PRODUCTS);

        $result = array_map(function ($product) {
            return $product->toArray();
        }, $products);

        return new JsonResponse($result);
    }
}
