<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class OrderNewRequestDTO
{
    /**
     * Список идентификаторов существующих товаров
     *
     * @var int[]
     */
    private $ids;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['ids'])) {
            $this->ids = $data['ids'];
        }
    }

    /**
     * @return int[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    /**
     * @param ClassMetadata $metadata
     *
     * @return void
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('ids',
            new Assert\NotBlank()
        );

        $metadata->addPropertyConstraint('ids',
            new Assert\All([
                new Assert\Type(['type' => 'integer'])
            ])
        );
    }
}
