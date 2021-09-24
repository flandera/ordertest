<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Collection;
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
class Order implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

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
     * @OneToMany(targetEntity="OrderProduct", mappedBy="order", cascade={"ALL"}, indexBy="products", fetch="EAGER")
     */
    private $products;

    /**
     * @param OrderProduct $product
     * @return Collection
     */
    public function addProduct(OrderProduct $product): Collection
    {
        $this->products[] =  $product;
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

    /**
     * @return string
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function jsonSerialize()
    {
        return (object) get_object_vars($this);
    }

    public function setProducts(array $products)
    {
        $this->products = $products;
    }

}
