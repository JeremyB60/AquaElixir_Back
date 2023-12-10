<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use DateTime;

class ProductReviewController extends AbstractController
{
    #[Route('/api/product/{productId}/reviews', name: 'review_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine, int $productId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $reviews = $entityManager
            ->getRepository(Review::class)
            ->findBy(
                ['productReview' => $productId],
                ['createdAt' => 'DESC'] // Tri par ordre décroissant de la date
            );



        $reviewsData = [];
        $currentDate = new DateTime();

        foreach ($reviews as $review) {
            $user = $review->getReviewUser();
            $lastname = $user->getLastName();

            $createdAt = $review->getCreatedAt();
            $interval = $currentDate->diff($createdAt);
            $daysDifference = $interval->days;

            if ($daysDifference === 0) {
                $date = "Aujourd'hui";
            } elseif ($daysDifference === 1) {
                $date = "Hier";
            } elseif ($daysDifference < 7) {
                $days = ceil($daysDifference / 1);
                $date = "Il y a $daysDifference jour" . ($days > 1 ? 's' : '');
            } elseif ($daysDifference < 30) {
                $weeks = ceil($daysDifference / 7);
                $date = "Il y a $weeks semaine" . ($weeks > 1 ? 's' : '');
            } else {
                $months = ceil($daysDifference / 30);
                $date = "Il y a $months mois";
            }
            $reviewsData[] = [
                'id' => $review->getId(),
                'rating' => $review->getRating(),
                'title' => $review->getTitle(),
                'comment' => $review->getComment(),
                'date' => $date,
                'firstname' => $user->getFirstName(),
                'lastname' => substr($lastname, 0, 1) . ".",
                // Ajoutez d'autres champs si nécessaire
            ];
        }

        return new JsonResponse($reviewsData);
    }

    #[Route('/api/product/{productId}/reviews', name: 'review_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, ManagerRegistry $doctrine, int $productId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        // Accéder à l'utilisateur actuel avec le token JWT
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $review = new Review();
        $review->setReviewUser($user);
        $review->setTitle($data['title']);
        $review->setRating($data['rating']);
        $review->setComment($data['comment']);

        $product = $entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        $review->setProductReview($product);
        $review->setCreatedAt(new \DateTime());

        $entityManager->persist($review);
        $entityManager->flush();
        // Actualiser l'entité pour s'assurer que l'identifiant est défini
        $entityManager->refresh($review);

        // Vérifier si la revue a été enregistrée avec succès
        if ($review->getId()) {
            dump($review);
            // La revue a été enregistrée avec succès
            return new JsonResponse(['success' => 'Review successfully saved'], 201);
        } else {
            // Une erreur s'est produite lors de l'enregistrement de la revue
            return new JsonResponse(['error' => 'Failed to save review'], 500);
        }
    }
}
