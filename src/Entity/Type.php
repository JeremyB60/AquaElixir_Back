<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $typeName = null;

    #[ORM\OneToMany(mappedBy: 'productType', targetEntity: Product::class)]
    private Collection $productType;

    public function __construct()
    {
        $this->productType = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): static
    {
        $this->typeName = $typeName;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProductType(): Collection
    {
        return $this->productType;
    }

    public function addProductType(Product $productType): static
    {
        if (!$this->productType->contains($productType)) {
            $this->productType->add($productType);
            $productType->setProductType($this);
        }

        return $this;
    }

    public function removeProductType(Product $productType): static
    {
        if ($this->productType->removeElement($productType)) {
            // set the owning side to null (unless already changed)
            if ($productType->getProductType() === $this) {
                $productType->setProductType(null);
            }
        }

        return $this;
    }
}
