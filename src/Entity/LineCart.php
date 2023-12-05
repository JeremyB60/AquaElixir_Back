<?php

namespace App\Entity;

use App\Repository\LineCartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LineCartRepository::class)]
class LineCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: "Cart", inversedBy: "lineItems")]
    #[ORM\JoinColumn(name: "cart_id", referencedColumnName: "id")]
    private $cart;

    #[ORM\ManyToOne(targetEntity: "Product")]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "id")]
    private $product;

    #[ORM\Column(type: "integer")]
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
