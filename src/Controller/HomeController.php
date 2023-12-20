<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/api/new-products", name="api_new_products", methods={"GET"})
     */
    public function getNewProducts(ProductRepository $productRepository): JsonResponse
    {
        // Récupérer tous les produits
        $allProducts = $productRepository->findAll();

        // Mélanger les produits de manière aléatoire
        shuffle($allProducts);

        // Prendre les trois premiers produits après le mélange
        $randomProducts = array_slice($allProducts, 0, 3);

        $formattedProducts = $this->formatProducts($randomProducts);

        return $this->json($formattedProducts);
        // $products = $productRepository->findBy(
        //     [],            // Aucun critère de recherche spécifique
        //     ['createdAt' => 'DESC'],  // Tri par ordre décroissant sur le champ 'created_at'
        //     3               // Limite de résultats à 3
        // );
        // $formattedProducts = $this->formatProducts($products);
    }

    /**
     * @Route("/api/popular-products", name="api_popular_products", methods={"GET"})
     */
    public function getPopularProducts(ProductRepository $productRepository): JsonResponse
    {
        $allProducts = $productRepository->findAll();
        shuffle($allProducts);
        $randomProducts = array_slice($allProducts, 0, 3);

        $formattedProducts = $this->formatProducts($randomProducts);

        return $this->json($formattedProducts);
        
        // $products = $productRepository->findBy(['name' => [
        //     'Complément beauté et bien-être', 'Probiotiques pour la flore intestinale',
        //     'Crème hydratante anti-âge',
        // ]], [], 3);
        // $formattedProducts = $this->formatProducts($products);
    }

    private function formatProducts(array $products): array
    {
        $formattedProducts = [];
        foreach ($products as $product) {
            $images = [];

            foreach ($product->getImages() as $image) {
                $images[] = [
                    'url' => $image->getUrl(),
                ];
            }

            $formattedProducts[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'images' => $images,
                'slug' => $product->getSlug(),
            ];
        }

        return $formattedProducts;
    }
}
