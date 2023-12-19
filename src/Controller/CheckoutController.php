<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\StripeClient;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/api/create-checkout-session", name: "api_create_checkout_session", methods: ["POST"])]
#[IsGranted('ROLE_USER')]
class CheckoutController extends AbstractController
{
    private $stripeSecretKey;
    private $yourDomain;

    public function __construct()
    {
        $this->stripeSecretKey = 'sk_test_51ONM1hBLaSzPsyD6VOMVr9rbdjCdxGRVvSVyO8jVBibfusIAWFYugYjzsKRLbvzn3bGYNfEAQFecZ5K3VQsQXtOh00Y0evnvfy';
        $this->yourDomain = 'https://localhost:5173';
    }

    public function createCheckoutSession(Request $request): Response
    {
        $stripe = new StripeClient($this->stripeSecretKey);

        $requestData = json_decode($request->getContent(), true);

        if ($requestData === null) {
            return new JsonResponse(['error' => 'Invalid JSON data in the request.'], Response::HTTP_BAD_REQUEST);
        }

        // Assurez-vous que le tableau 'cartItems' existe dans la requête JSON
        if (!isset($requestData['cartItems']) || !is_array($requestData['cartItems'])) {
            return new JsonResponse(['error' => 'Invalid or missing cartItems data.'], Response::HTTP_BAD_REQUEST);
        }

        $lineItems = [];

        foreach ($requestData['cartItems'] as $cartItem) {
            // Vérifiez que 'price' et 'quantity' sont définis dans chaque élément du panier
            if (!isset($cartItem['price'], $cartItem['quantity'])) {
                return new JsonResponse(['error' => 'Invalid cart item data.'], Response::HTTP_BAD_REQUEST);
            }

            $lineItems[] = [
                'price' => $cartItem['price'],
                'quantity' => $cartItem['quantity'],
            ];
        }

        try {
            $checkout_session = $stripe->checkout->sessions->create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'ui_mode' => "embedded",
                'return_url' => $this->yourDomain . '/return?session_id={CHECKOUT_SESSION_ID}',
            ]);
            dump($checkout_session);
            return new JsonResponse(['clientSecret' => $checkout_session->client_secret]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
