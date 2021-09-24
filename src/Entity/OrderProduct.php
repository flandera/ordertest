<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderProductsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Column;

/**
 * @ORM\Entity(repositoryClass=OrderProductsRepository::class)
 */
class OrderProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ManyToOne(targetEntity="Order", inversedBy="products") */
    private Order $order;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $price;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $pieces;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPieces(): ?int
    {
        return $this->pieces;
    }

    public function setPieces(int $pieces): self
    {
        $this->pieces = $pieces;

        return $this;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;
        return $this;
    }
}
