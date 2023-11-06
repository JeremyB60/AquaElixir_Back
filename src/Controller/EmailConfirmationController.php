<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EmailConfirmationController
{
    /**
     * @Route("/confirm-email/{token}", name="confirm_email", methods={"GET"})
     */
    public function confirmEmail(string $token, UserService $userService): JsonResponse
    {
        $result = $userService->confirmEmail($token);

        if ($result->isSuccess()) {
            return new JsonResponse(['message' => 'Email confirmed.']);
        } else {
            return new JsonResponse(['message' => 'Email confirmation failed'], 400);
        }
    }
}
