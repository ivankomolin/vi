<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * Статус "новый"
     */
    const STATUS_NEW = 'new';

    /**
     * Статус "оплачен"
     */
    const STATUS_PAID = 'paid';

    /**
     * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
     *
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $status;

    /**
     * @ORM\Column(type="string", columnDefinition="double(10,2)")
     *
     * @var string
     */
    public $amount;

    /**
     * @ORM\ManyToMany(targetEntity="Product")
     * @ORM\JoinTable(name="orders_products",
     *      joinColumns={@ORM\JoinColumn(name="order_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     * )
     *
     * @var ArrayCollection
     */
    public $products;

    /**
     * Загрузка первоначальных значений сущности
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->status = self::STATUS_NEW;
        $this->amount = 0;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'amount' => (float)$this->amount,
        ];
    }
}
