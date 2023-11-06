<?php

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserDTO
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    public ?string $firstName = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    public ?string $lastName = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public ?string $email = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8)
     */
    public ?string $password = null;


    public function __construct(
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $email = null,
        ?string $password = null
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
    }

    public static function createFromRequest(Request $request): RegisterUserDTO
    {
        $data = json_decode($request->getContent(), true);

        // Validez et définissez les propriétés du DTO ici.
        $registerUserDTO = new self();
        $registerUserDTO->firstName = $data['firstName'];
        $registerUserDTO->lastName = $data['lastName'];
        $registerUserDTO->email = $data['email'];
        $registerUserDTO->password = $data['password'];

        return $registerUserDTO;
    }

}
