<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class OrderPayRequestDTO
{
    /**
     * Идентификатор существуюшего заказа
     *
     * @var int
     */
    private $id;

    /**
     * Сумма заказа
     *
     * @var float
     */
    private $amount;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $id = (int)$request->get('id');
        if (!empty($id)) {
            $this->id = $id;
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['amount'])) {
            $this->amount = $data['amount'];
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param ClassMetadata $metadata
     *
     * @return void
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('id',
            new Assert\NotBlank()
        );

        $metadata->addPropertyConstraint('amount',
            new Assert\NotBlank()
        );

        $metadata->addPropertyConstraint('amount',
            new Assert\Type(['type' => 'float'])
        );
    }
}
