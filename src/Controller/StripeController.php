<?php

// src/Controller/StripeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/api/status', name: 'stripe_status', methods: ['POST'])]
    public function checkStatus(Request $request): JsonResponse
    {
        $stripeSecretKey = $this->getParameter('stripe_secret_key'); // Obtenez la clé secrète depuis les paramètres Symfony

        try {
            // Récupérer le JSON du corps de la requête POST
            $jsonStr = $request->getContent();
            $jsonObj = json_decode($jsonStr);

            // Initialiser le client Stripe
            $stripe = new \Stripe\StripeClient($stripeSecretKey);

            // Récupérer la session Stripe
            $session = $stripe->checkout->sessions->retrieve($jsonObj->session_id);
            dump($session);
            // Retourner la réponse JSON avec le statut et l'adresse e-mail du client
            return new JsonResponse(['status' => $session->status, 'customer_email' => $session->customer_details->email], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Gérer les erreurs et retourner une réponse JSON avec le code HTTP 500
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
