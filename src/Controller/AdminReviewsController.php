<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReviewRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

class AdminReviewsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /* RECUPERER LA LISTE DES AVIS SIGNALÉS */
    #[Route("/api/reports", name: "api_reports_list", methods: ["GET"])]
    #[IsGranted('ROLE_ADMIN')]
    public function getReports(ReviewRepository $reviewRepository): JsonResponse
    {
        $reports = $reviewRepository->findBy(['report' => !null], ['report' => 'ASC']);

        $formattedReports = [];
        foreach ($reports as $report) {
            $formattedReports[] = [
                'id' => $report->getId(),
                'title' => $report->getTitle(),
                'comment' => $report->getComment(),
                $user = $report->getReviewUser(),
                'email' => $user->getEmail(),
                'createdAt' => $report->getCreatedAt()->format('d-m-Y'),
            ];
        }

        return $this->json($formattedReports, 200, [], ['groups' => 'report']);
    }

    /* SUPPRIMER UN AVIS */
    #[Route("/api/report/{id}", name: "api_report_delete", methods: ["DELETE"])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteReport(ReviewRepository $reviewRepository, ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $report = $reviewRepository->find($id);

        if (!$report) {
            return $this->json(['error' => 'Avis non trouvé'], 404);
        }

        $product = $report->getProductReview(); // Récupérer le produit associé à la revue

        try {
            // Supprimez l'avis de la base de données
            $entityManager = $doctrine->getManager();
            $entityManager->remove($report);
            $entityManager->flush();

            // Mise à jour de la moyenne après la suppression de la revue
            $this->updateAverageRating($product);

            return $this->json(['message' => 'Avis supprimé avec succès']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Une erreur s\'est produite lors de la suppression de l\'avis'], 500);
        }
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
