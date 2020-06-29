<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\DTO;
use GuzzleHttp\Client;
use App\Exception\ContextException;

class OrderService
{
    /**
     * Адрес платежного сервиса
     */
    const PAID_SERVICE = 'http://ya.ru';

    /**
     * Таймаут для работы с платежным сервисом
     */
    const PAID_SERVICE_TIMEOUT = 3;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var Client
     */
    private $guzzle;

    /**
     * @param EntityManager $entityManager
     * @param ProductRepository $productRepository
     * @param OrderRepository $orderRepository
     * @param Client $guzzle
     */
    public function __construct(
        EntityManager $entityManager,
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        Client $guzzle
    ) {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->guzzle = $guzzle;
    }

    /**
     * @param DTO\OrderNewRequestDTO $requestDTO
     *
     * @return Order
     */
    public function create(DTO\OrderNewRequestDTO $requestDTO): Order
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $ids = $requestDTO->getIds();
            $products = $this->productRepository->findBy(['id' => $ids]);
            if (count($products) !== count($ids)) {
                throw new UnprocessableHttpException('Unavailable product');
            }

            $order = new Order;
            foreach ($products as $product) {
                $order->amount = bcadd($order->amount,
                    $product->price,
                    $product::PRICE_SCALE);
                $order->products->add($product);
                $this->entityManager->persist($product);
            }

            $this->entityManager->persist($order);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return $order;
    }

    /**
     * @param DTO\OrderPayRequestDTO $requestDTO
     *
     * @return Order
     */
    public function pay(DTO\OrderPayRequestDTO $requestDTO): Order
    {
        $order = $this->orderRepository->find($requestDTO->getId());
        if (empty($order) || $order->status !== $order::STATUS_NEW) {
            throw new ContextException('The action is not available');
        }

        if (bccomp($order->amount,
            $requestDTO->getAmount(),
            Product::PRICE_SCALE) !== 0
        ) {
            throw new ContextException('Amount is diverge');
        }

        try {
            $response = $this->guzzle->request('GET', self::PAID_SERVICE, [
                'timeout' => self::PAID_SERVICE_TIMEOUT,
            ]);
        } catch (\Exception $e) {
            throw new ContextException('Payment gateway does not respond');
        }

        if ($response->getStatusCode() !== 200) {
            throw new ContextException('Payment failed');
        }

        $order->status = $order::STATUS_PAID;
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }
}
