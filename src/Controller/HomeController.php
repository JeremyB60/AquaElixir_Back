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
        $products = $productRepository->findBy(['name' => [
            'Spray hydratant',
            'Masque purifiant et revitalisant', 'Baume à lèvres hydratant'
        ]], [], 3);

        $formattedProducts = $this->formatProducts($products);

        return $this->json($formattedProducts);
    }

    /**
     * @Route("/api/popular-products", name="api_popular_products", methods={"GET"})
     */
    public function getPopularProducts(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findBy(['name' => [
            'Complément beauté et bien-être', 'Probiotiques pour la flore intestinale',
            'Crème hydratante anti-âge',
        ]], [], 3);

        $formattedProducts = $this->formatProducts($products);

        return $this->json($formattedProducts);
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
