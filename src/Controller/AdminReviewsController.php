<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReviewRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminReviewsController extends AbstractController
{
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

    #[Route("/api/report/{id}", name: "api_report_delete", methods: ["DELETE"])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteReport(ReviewRepository $reviewRepository, ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $report = $reviewRepository->find($id);

        if (!$report) {
            return $this->json(['error' => 'Avis non trouvé'], 404);
        }

        try {
            // Supprimez l'avis de la base de données
            $entityManager = $doctrine->getManager();
            $entityManager->remove($report);
            $entityManager->flush();

            return $this->json(['message' => 'Avis supprimé avec succès']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Une erreur s\'est produite lors de la suppression de l\'avis'], 500);
        }
    }
}
