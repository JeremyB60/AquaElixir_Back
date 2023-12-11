<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;

class DetailProductController extends AbstractController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/api/product/{slug}", name="api_product_view")
     */
    public function viewProduct($slug)
    {
        // Récupérer les données du produit en fonction du slug
        $product = $this->productRepository->findOneBy(['slug' => $slug]);

        // Vérifier si le produit existe
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        // Convertir les données en JSON et les renvoyer
        $formattedProduct = $this->formatEntity($product);

        return $this->json($formattedProduct);
    }

    private function formatEntity(Product $product): array
    {
        $images = [];

        foreach ($product->getImages() as $image) {
            $images[] = [
                'url' => $image->getUrl(),
            ];
        }
        $type = $product->getProductType();

        if ($type) {
            $typeData['id'] = $type->getId();
            $typeData['name'] = $type->getTypeName();
            $typeData['slug'] = $type->getSlug();

            $parentType = $type->getParent();
            if ($parentType) {
                $typeData['parent'] = [
                    'id' => $parentType->getId(),
                    'name' => $parentType->getTypeName(),
                    'slug' => $parentType->getSlug(),
                ];
            }
        }

        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'taxe' => $product->getTaxe(),
            'slug' => $product->getSlug(),
            'mesurement' => $product->getMesurement(),
            'description' => $product->getDescription(),
            'detailedDescription' => $product->getDetailedDescription(),
            'images' => $images,
            'type' => $typeData,
        ];
    }
}
