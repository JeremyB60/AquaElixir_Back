<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TypeRepository;

class TypeController extends AbstractController
{
    private $typeRepository;
    private $productRepository;

    public function __construct(TypeRepository $typeRepository, ProductRepository $productRepository)
    {
        $this->typeRepository = $typeRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/api/subtype/{slug}", name="api_subtype_view")
     */
    public function viewSubtype($slug)
    {
        // Récupérer les données du type en fonction du slug
        $type = $this->typeRepository->findOneBy(['slug' => $slug]);

        // Vérifier si le type existe
        if (!$type) {
            return new JsonResponse(['error' => 'Type not found'], 404);
        }

        // Récupérer l'ID du type
        $typeId = $type->getId();

        // Récupérer tous les produits liés à ce type en utilisant l'ID du type
        $products = $this->productRepository->findBy(['productType' => $typeId]);

        // Convertir les données en JSON et les renvoyer
        $formattedType = $this->formatEntity($type, $products);

        return $this->json($formattedType);
    }

    /**
     * @Route("/api/type/{slug}", name="api_type_view")
     */
    public function viewType($slug)
    {
        // Récupérer les données du type en fonction du slug
        $parentType = $this->typeRepository->findOneBy(['slug' => $slug]);

        // Vérifier si le type existe
        if (!$parentType) {
            return new JsonResponse(['error' => 'Type not found'], 404);
        }

        // Récupérer l'ID du type
        $typeParentId = $parentType->getId();

        // Récupérer tous les ID ayant le même parentID
        $types = $this->typeRepository->findBy(['parent' => $typeParentId]);

        // Récupérer les ID des sous-catégories
        $typeIds = [];
        foreach ($types as $type) {
            $typeIds[] = $type->getId();
        }

        // Récupérer tous les produits liés aux sous-catégories
        $products = $this->productRepository->findBy(['productType' => $typeIds]);

        // Convertir les données en JSON et les renvoyer
        $formattedType = $this->formatEntity2($parentType, $types, $products);

        return $this->json($formattedType);
    }

    private function formatEntity2($parentType, $types, $products): array
    {
        $formattedEntities = [];

        // Include $parentType in the first position
        $formattedEntities[] = $this->formatSingleEntity($parentType, $products);

        foreach ($types as $type) {
            // Skip $parentType as it's already included
            if ($type->getId() === $parentType->getId()) {
                continue;
            }

            $formattedEntities[] = $this->formatSingleEntity($type, $products);
        }

        return $formattedEntities;
    }

    private function formatSingleEntity($type, $products): array
    {
        $formattedProducts = [];

        foreach ($products as $product) {
            // Vérifiez si le produit appartient au type actuel
            if ($product->getProductType() && $product->getProductType()->getId() === $type->getId()) {
                
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
                    'slug' => $product->getSlug(),
                    'images' => $images,
                    'averageReview' => $product->getAverageReview()
                    // Ajoutez d'autres champs si nécessaire
                ];
            }
        }

        return [
            'id' => $type->getId(),
            'name' => $type->getTypeName(),
            'slug' => $type->getSlug(),
            'parent' => [
                'id' => $type->getParent() ? $type->getParent()->getId() : null,
                'name' => $type->getParent() ? $type->getParent()->getTypeName() : null,
                'slug' => $type->getParent() ? $type->getParent()->getSlug() : null,
            ],
            'products' => $formattedProducts,
        ];
    }



    private function formatEntity($type, $products): array
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
                'slug' => $product->getSlug(),
                'images' => $images,
                'averageReview' => $product->getAverageReview()
                // Ajoutez d'autres champs si nécessaire
            ];
        }

        return [
            'id' => $type->getId(),
            'name' => $type->getTypeName(),
            'slug' => $type->getSlug(),
            'parent' => [
                'id' => $type->getParent()->getId(),
                'name' => $type->getParent()->getTypeName(),
                'slug' => $type->getParent()->getSlug(),
            ],
            'products' => $formattedProducts,
        ];
    }
}
