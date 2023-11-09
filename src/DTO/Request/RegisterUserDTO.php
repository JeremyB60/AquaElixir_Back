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
    public string $firstName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    public string $lastName;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public string $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8)
     */
    public string $password;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $password
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
    }

    public static function createFromRequest(Request $request): RegisterUserDTO
    {
        $data = json_decode($request->getContent(), true);

        // Validate and set the DTO properties here.
        return new self(
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['password']
        );
    }
}


