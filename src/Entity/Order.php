<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Column;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @UniqueEntity(
 *     fields={"orderId", "partnerId"},
 *     errorPath="orderId",
 *     message="This orderId is already in use on that partnerId."
 * )
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private string $id;

    /** @Column(type="string") */
    private string $orderId;

    /** @Column(type="string") */
    private string $partnerId;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $deliveryDate;

    /**
     * @ORM\Column(type="float")
     */
    private float $orderTotal;

    /**
     * @OneToMany(targetEntity="OrderProduct", mappedBy="order", cascade={"ALL"}, indexBy="products")
     */
    private array $products;

    public function addProduct($name, $value): array
    {
        $this->products[$name] = new OrderProduct($name, $value, $this);
        return $this->products;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): Order
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getPartnerId(): ?string
    {
        return $this->partnerId;
    }

    public function setPartnerId(string $partnerId): Order
    {
        $this->partnerId = $partnerId;
        return $this;
    }

    public function getDeliveryDate(): ?\DateTime
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate($deliveryDate): Order
    {
        $this->deliveryDate = $deliveryDate;
        return $this;
    }

    public function getOrderTotal(): ?float
    {
        return $this->orderTotal;
    }

    public function setOrderTotal(float $orderTotal): Order
    {
        $this->orderTotal = $orderTotal;
        return $this;
    }

}
