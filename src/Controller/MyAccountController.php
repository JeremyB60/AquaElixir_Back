<?php

// src/Controller/MyAccountController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\Persistence\ManagerRegistry;


class MyAccountController extends AbstractController
{
    #[Route('/api/modify-user', name: 'api_modify_user', methods: ['PUT'])]
    public function updateCurrentUser(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine): JsonResponse
    {
        // Accéder à l'utilisateur actuel avec le token JWT
        $currentUser = $this->getUser();

        // Désérialiser les données JSON directement dans l'objet User
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        // Mettez à jour les propriétés de l'utilisateur
        $currentUser->setFirstName($data['firstName'] ?? $currentUser->getFirstName());
        $currentUser->setLastName($data['lastName'] ?? $currentUser->getLastName());

        $entityManager = $doctrine->getManager();
        $entityManager->flush();

        // Serialize the updated user to JSON
        $jsonContent = $serializer->serialize($currentUser, 'json');

        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }
}
