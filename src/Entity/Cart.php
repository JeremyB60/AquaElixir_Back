<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\CartRepository")]
class Cart
{

    use CreatedAtTrait; // Utilisation du trait pour gérer les timestamps

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", unique: true)]
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="LineCart", mappedBy="cart", cascade={"persist"})
     */
    private $lineItems;

    public function __construct()
    {
        $this->lineItems = new ArrayCollection();
    }

    // Autres propriétés et méthodes de la table Cart
    /**
     * Setter pour définir l'ID de l'utilisateur dans la table Cart.
     *
     * @param int|null $userId
     * @return $this
     */

    public function getUser(): ?int
    {
        return $this->user ? $this->user->getId() : null;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|LineCart[]
     */
    public function getLineItems(): Collection
    {
        return $this->lineItems;
    }

    public function addLineItem(LineCart $lineItem): self
    {
        if (!$this->lineItems->contains($lineItem)) {
            $this->lineItems[] = $lineItem;
            $lineItem->setCart($this);
        }

        return $this;
    }

    public function removeLineItem(LineCart $lineItem): self
    {
        if ($this->lineItems->removeElement($lineItem)) {
            // set the owning side to null (unless already changed)
            if ($lineItem->getCart() === $this) {
                $lineItem->setCart(null);
            }
        }

        return $this;
    }
}
