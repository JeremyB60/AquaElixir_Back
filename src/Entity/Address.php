<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $extendedAddress = null;

    #[ORM\Column(length: 20)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 50)]
    private ?string $city = null;

    #[ORM\Column(length: 50)]
    private ?string $country = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?AddressCategory $addressCategory = null;

    #[ORM\OneToMany(mappedBy: 'deliveryAddress', targetEntity: Order::class)]
    private Collection $deliveryAddress;

    #[ORM\OneToMany(mappedBy: 'invoiceAddress', targetEntity: Order::class)]
    private Collection $invoiceAddress;

    #[ORM\ManyToOne(inversedBy: 'userAddress')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userAddress = null;

    public function __construct()
    {
        $this->deliveryAddress = new ArrayCollection();
        $this->invoiceAddress = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getExtendedAddress(): ?string
    {
        return $this->extendedAddress;
    }

    public function setExtendedAddress(?string $extendedAddress): static
    {
        $this->extendedAddress = $extendedAddress;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getAddressCategory(): ?AddressCategory
    {
        return $this->addressCategory;
    }

    public function setAddressCategory(?AddressCategory $addressCategory): static
    {
        $this->addressCategory = $addressCategory;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getDeliveryAddress(): Collection
    {
        return $this->deliveryAddress;
    }

    public function addDeliveryAddress(Order $deliveryAddress): static
    {
        if (!$this->deliveryAddress->contains($deliveryAddress)) {
            $this->deliveryAddress->add($deliveryAddress);
            $deliveryAddress->setDeliveryAddress($this);
        }

        return $this;
    }

    public function removeDeliveryAddress(Order $deliveryAddress): static
    {
        if ($this->deliveryAddress->removeElement($deliveryAddress)) {
            // set the owning side to null (unless already changed)
            if ($deliveryAddress->getDeliveryAddress() === $this) {
                $deliveryAddress->setDeliveryAddress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getInvoiceAddress(): Collection
    {
        return $this->invoiceAddress;
    }

    public function addInvoiceAddress(Order $invoiceAddress): static
    {
        if (!$this->invoiceAddress->contains($invoiceAddress)) {
            $this->invoiceAddress->add($invoiceAddress);
            $invoiceAddress->setInvoiceAddress($this);
        }

        return $this;
    }

    public function removeInvoiceAddress(Order $invoiceAddress): static
    {
        if ($this->invoiceAddress->removeElement($invoiceAddress)) {
            // set the owning side to null (unless already changed)
            if ($invoiceAddress->getInvoiceAddress() === $this) {
                $invoiceAddress->setInvoiceAddress(null);
            }
        }

        return $this;
    }

    public function getUserAddress(): ?User
    {
        return $this->userAddress;
    }

    public function setUserAddress(?User $userAddress): static
    {
        $this->userAddress = $userAddress;

        return $this;
    }

}
