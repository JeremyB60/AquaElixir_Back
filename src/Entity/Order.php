<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $totalAmount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentStatus = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryStatus = null;

    #[ORM\ManyToOne(inversedBy: '_order')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $address = null;

    #[ORM\ManyToOne(inversedBy: '_order2')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $address2 = null;

    #[ORM\ManyToOne(inversedBy: 'deliveryAddress')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $deliveryAddress = null;

    #[ORM\ManyToOne(inversedBy: 'invoiceAddress')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $invoiceAddress = null;

    #[ORM\OneToMany(mappedBy: 'lineProductOrder', targetEntity: LineProduct::class)]
    private Collection $lineProducts;

    #[ORM\OneToMany(mappedBy: 'orderReturnedProduct', targetEntity: ReturnedProduct::class)]
    private Collection $orderReturnedProduct;

    #[ORM\ManyToOne(inversedBy: 'orderUser')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $orderUser = null;

    public function __construct()
    {
        $this->lineProducts = new ArrayCollection();
        $this->orderReturnedProduct = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(string $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): static
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(string $paymentStatus): static
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getDeliveryStatus(): ?string
    {
        return $this->deliveryStatus;
    }

    public function setDeliveryStatus(string $deliveryStatus): static
    {
        $this->deliveryStatus = $deliveryStatus;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress2(): ?Address
    {
        return $this->address2;
    }

    public function setAddress2(?Address $address2): static
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getDeliveryAddress(): ?Address
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?Address $deliveryAddress): static
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getInvoiceAddress(): ?Address
    {
        return $this->invoiceAddress;
    }

    public function setInvoiceAddress(?Address $invoiceAddress): static
    {
        $this->invoiceAddress = $invoiceAddress;

        return $this;
    }

    /**
     * @return Collection<int, LineProduct>
     */
    public function getLineProducts(): Collection
    {
        return $this->lineProducts;
    }

    public function addLineProduct(LineProduct $lineProduct): static
    {
        if (!$this->lineProducts->contains($lineProduct)) {
            $this->lineProducts->add($lineProduct);
            $lineProduct->setLineProductOrder($this);
        }

        return $this;
    }

    public function removeLineProduct(LineProduct $lineProduct): static
    {
        if ($this->lineProducts->removeElement($lineProduct)) {
            // set the owning side to null (unless already changed)
            if ($lineProduct->getLineProductOrder() === $this) {
                $lineProduct->setLineProductOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReturnedProduct>
     */
    public function getOrderReturnedProduct(): Collection
    {
        return $this->orderReturnedProduct;
    }

    public function addOrderReturnedProduct(ReturnedProduct $orderReturnedProduct): static
    {
        if (!$this->orderReturnedProduct->contains($orderReturnedProduct)) {
            $this->orderReturnedProduct->add($orderReturnedProduct);
            $orderReturnedProduct->setOrderReturnedProduct($this);
        }

        return $this;
    }

    public function removeOrderReturnedProduct(ReturnedProduct $orderReturnedProduct): static
    {
        if ($this->orderReturnedProduct->removeElement($orderReturnedProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderReturnedProduct->getOrderReturnedProduct() === $this) {
                $orderReturnedProduct->setOrderReturnedProduct(null);
            }
        }

        return $this;
    }

    public function getOrderUser(): ?User
    {
        return $this->orderUser;
    }

    public function setOrderUser(?User $orderUser): static
    {
        $this->orderUser = $orderUser;

        return $this;
    }
}
