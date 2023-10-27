<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    private ?string $lastName = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $role = [];

    #[ORM\Column(length: 50)]
    private ?string $accountStatus = null;

    #[ORM\OneToOne(mappedBy: 'cartUser', cascade: ['persist', 'remove'])]
    private ?Cart $cartUser = null;

    #[ORM\OneToMany(mappedBy: 'orderUser', targetEntity: Order::class)]
    private Collection $orderUser;

    #[ORM\OneToMany(mappedBy: 'reviewUser', targetEntity: Review::class)]
    private Collection $reviewUser;

    #[ORM\OneToMany(mappedBy: 'userAddress', targetEntity: Address::class)]
    private Collection $userAddress;

    public function __construct()
    {
        $this->orderUser = new ArrayCollection();
        $this->reviewUser = new ArrayCollection();
        $this->userAddress = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getAccountStatus(): ?string
    {
        return $this->accountStatus;
    }

    public function setAccountStatus(string $accountStatus): static
    {
        $this->accountStatus = $accountStatus;

        return $this;
    }

    public function getCartUser(): ?Cart
    {
        return $this->cartUser;
    }

    public function setCartUser(Cart $cartUser): static
    {
        // set the owning side of the relation if necessary
        if ($cartUser->getCartUser() !== $this) {
            $cartUser->setCartUser($this);
        }

        $this->cartUser = $cartUser;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrderUser(): Collection
    {
        return $this->orderUser;
    }

    public function addOrderUser(Order $orderUser): static
    {
        if (!$this->orderUser->contains($orderUser)) {
            $this->orderUser->add($orderUser);
            $orderUser->setOrderUser($this);
        }

        return $this;
    }

    public function removeOrderUser(Order $orderUser): static
    {
        if ($this->orderUser->removeElement($orderUser)) {
            // set the owning side to null (unless already changed)
            if ($orderUser->getOrderUser() === $this) {
                $orderUser->setOrderUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviewUser(): Collection
    {
        return $this->reviewUser;
    }

    public function addReviewUser(Review $reviewUser): static
    {
        if (!$this->reviewUser->contains($reviewUser)) {
            $this->reviewUser->add($reviewUser);
            $reviewUser->setReviewUser($this);
        }

        return $this;
    }

    public function removeReviewUser(Review $reviewUser): static
    {
        if ($this->reviewUser->removeElement($reviewUser)) {
            // set the owning side to null (unless already changed)
            if ($reviewUser->getReviewUser() === $this) {
                $reviewUser->setReviewUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getUserAddress(): Collection
    {
        return $this->userAddress;
    }

    public function addUserAddress(Address $userAddress): static
    {
        if (!$this->userAddress->contains($userAddress)) {
            $this->userAddress->add($userAddress);
            $userAddress->setUserAddress($this);
        }

        return $this;
    }

    public function removeUserAddress(Address $userAddress): static
    {
        if ($this->userAddress->removeElement($userAddress)) {
            // set the owning side to null (unless already changed)
            if ($userAddress->getUserAddress() === $this) {
                $userAddress->setUserAddress(null);
            }
        }

        return $this;
    }
}
