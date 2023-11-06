<?php

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordDTO
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public ?string $email = null;

    public static function createFromRequest(Request $request): ResetPasswordDTO
    {
        $data = json_decode($request->getContent(), true);

        // Validez et définissez les propriétés du DTO ici.
        $resetPasswordDTO = new self();
        $resetPasswordDTO->email = $data['email'];

        return $resetPasswordDTO;
    }
}
