<?php

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ModifyAccountDTO
{
    /**
     * @Assert\NotBlank(message="First name is required")
     * @Assert\Length(min=2, max=50
     */
    private ?string $firstName;

    /**
     * @Assert\NotBlank(message="Last name is required")
     * @Assert\Length(min=2, max=50
     */
    private ?string $lastName;

    /**
     * @Assert\NotBlank(message="Password is required")
     * @Assert\Password()
     * @Assert\Length(min=8)
     */
    private ?string $password;

    /**
     * @Assert\NotBlank(message="Current password is required for modification")
     * @Assert\Password()
     * @Assert\Length(min=8)
     */
    private ?string $currentPassword;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCurrentPassword(): ?string
    {
        return $this->currentPassword;
    }

    public function setCurrentPassword(?string $currentPassword): self
    {
        $this->currentPassword = $currentPassword;

        return $this;
    }

    public function __construct(
        ?string $firstName,
        ?string $lastName,
        ?string $password,
        ?string $currentPassword
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
        $this->currentPassword = $currentPassword;
    }


    public static function createFromRequest(Request $request): ModifyAccountDTO
    {
        $data = json_decode($request->getContent(), true);

        return new self(
            $data['firstName'] ?? null,
            $data['lastName'] ?? null,
            $data['password'] ?? null,
            $data['currentPassword'] ?? null
        );
    }
}
