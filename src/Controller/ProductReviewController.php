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
use Doctrine\ORM\EntityManagerInterface;

class ProductReviewController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /*AFFICHE LES AVIS DES CLIENTS CONCERNANT LE PRODUIT*/
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
                'email' => $user->getEmail(),
                'firstname' => $user->getFirstName(),
                'lastname' => substr($lastname, 0, 1) . ".",
                // Ajoutez d'autres champs si nécessaire
            ];
        }

        return new JsonResponse($reviewsData);
    }

    /* AJOUTER UN AVIS */
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
        // Mise à jour de la moyenne après l'ajout de la revue
        $this->updateAverageRating($product);

        // Vérifier si la revue a été enregistrée avec succès
        if ($review->getId()) {
            // La revue a été enregistrée avec succès
            return new JsonResponse(['success' => 'Review successfully saved'], 201);
        } else {
            // Une erreur s'est produite lors de l'enregistrement de la revue
            return new JsonResponse(['error' => 'Failed to save review'], 500);
        }
    }
    /* SUPPRIMER UN AVIS */
    #[Route('/api/reviews/{reviewId}', name: 'delete_review', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteReview($reviewId, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $user = $this->getUser();

        $review = $entityManager->getRepository(Review::class)->find($reviewId);

        if (!$review) {
            return new JsonResponse(['error' => 'Review not found'], 500);
        }

        // Vérifier si l'utilisateur actuel est l'auteur de l'avis
        if ($review->getReviewUser() !== $user) {
            return new JsonResponse(['error' => 'Unauthorized'], 403);
        }

        $entityManager->remove($review);
        $entityManager->flush();

        // Mise à jour de la moyenne après la suppression de la revue
        $this->updateAverageRating($review->getProductReview());

        return new JsonResponse(['message' => 'Review deleted successfully'], 201);
    }

    /* SIGNALER UN AVIS */
    #[Route('/report/{reviewId}', name: 'report_review', methods: ['POST'])]
    public function reportReview($reviewId, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $review = $entityManager->getRepository(Review::class)->find($reviewId);

        // Vérifier si l'avis existe
        if (!$review) {
            return new JsonResponse(['error' => 'Review not found'], 404);
        }

        // Ajouter 1 au champ "report" de l'avis
        $reportCount = $review->getReport();
        $review->setReport($reportCount + 1);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Review reported successfully'], 201);
    }

    // Fonction pour mettre à jour la moyenne des avis
    private function updateAverageRating(Product $product): void
    {
        $reviews = $product->getProductReview();
        $totalReviews = count($reviews);

        if ($totalReviews > 0) {
            $totalRating = 0;

            foreach ($reviews as $review) {
                $totalRating += $review->getRating();
            }
            $averageRating = $totalRating / $totalReviews;
            $product->setAverageReview($averageRating);

            $this->entityManager->persist($product);
            $this->entityManager->flush();
        } else {
            // Si aucune revue, la moyenne est 0 (ou une valeur par défaut)
            $product->setAverageReview(0);
        }
    }
}
