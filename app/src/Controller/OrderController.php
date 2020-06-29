<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\InvalidRequestHttpException;
use App\Exception\UnprocessableHttpException;
use App\Exception\ContextException;
use App\DTO;

class OrderController
{
    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param OrderService $orderService
     * @param ValidatorInterface $validator
     */
    public function __construct(OrderService $orderService, ValidatorInterface $validator)
    {
        $this->orderService = $orderService;
        $this->validator = $validator;
    }

    /**
     * Создание нового заказа
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $requestDTO = new DTO\OrderNewRequestDTO($request);
        $errors = $this->validator->validate($requestDTO);
        if (count($errors) > 0) {
            throw new InvalidRequestHttpException($errors);
        }

        try {
            $order = $this->orderService->create($requestDTO);
        } catch (ContextException $e) {
            throw new UnprocessableHttpException($e->getMessage());
        }
        
        return new JsonResponse($order->toArray());
    }

    /**
     * Оплата существующего заказа
     *
     * @param  Request $request
     *
     * @return JsonResponse
     */
    public function pay(Request $request): JsonResponse
    {
        $requestDTO = new DTO\OrderPayRequestDTO($request);
        $errors = $this->validator->validate($requestDTO);
        if (count($errors) > 0) {
            throw new InvalidRequestHttpException($errors);
        }

        try {
            $order = $this->orderService->pay($requestDTO);
        } catch (ContextException $e) {
            throw new UnprocessableHttpException($e->getMessage());
        }
        
        return new JsonResponse($order->toArray());
    }
}
