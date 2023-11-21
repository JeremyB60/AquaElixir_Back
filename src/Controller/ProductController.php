<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use JMS\Serializer\SerializationContext;
use App\Service\VersionningService;
use Symfony\Component\Serializer\SerializerInterface;


class ProductController extends AbstractController
{
    #[Route('/products', name: 'get_products', methods: ['GET'])]
    public function getProducts(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        $productData = [];

        foreach ($products as $product) {
            
            if ($product->getStock() > 0) {
                $productData[] = [
                    'id' => $product->getId(),
                    'Nom' => $product->getName(),
                    'Stock' => $product->getStock(),
                    'Référence' => $product->getReference(),
                    'Prix' => $product->getPrice(),
                    'Taxe' => $product->getTaxe(),
                    'Description courte' => $product->getDescription(),
                    'Conditionnement' => $product->getMesurement()                    
                ];
            }
        }

        return $this->json($productData, Response::HTTP_OK);
    }

    
    #[Route('/products/{id}', name: 'detailProduct', methods: ['GET'])]
    public function getProduct($id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            // Gérez ici le cas où le produit n'est pas trouvé (par exemple, renvoyez une erreur 404)
            return new JsonResponse(['message' => 'Produit non trouvé'], 404);
        }

        // Construisez un tableau avec les caractéristiques du produit
        $produitData = [
            'idProduit' => $product->getId(),
            'Nom' => $product->getName(),
            'Stock' => $product->getStock(),
            'Référence' => $product->getReference(),
            'Prix' => $product->getPrice(),
            'Taxe' => $product->getTaxe(),
            'Description détaillée' => $product->getDetailedDescription(),
            'Conditionnement' => $product->getMesurement()      
        ];

        return new JsonResponse($produitData);
    }
}