<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\LineCart;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\OptimisticLockException;

class CartController extends AbstractController
{
    #[Route('/api/get-user-cart', name: 'api_get_user_cart', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUserCart(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $user = $this->getUser();

        $cart = $entityManager->getRepository(Cart::class)->findOneBy(['user' => $user]);

        if (!$cart) {
            return $this->json(['cartItems' => []]);
        }

        $cartItems = $entityManager->getRepository(LineCart::class)->findBy(['cart' => $cart]);

        $formattedCartItems = [];
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->getProduct();
            $typeData = $this->formatEntity($product);

            // Retrieve additional product details as needed
            $productDetails = [
                'productId' => $product->getId(),
                'productName' => $product->getName(),
                'productPrice' => $product->getPrice(),
                'productStripePriceId' => $product->getStripePriceId(),
                'productSlug' => $product->getSlug(),
                'productMesurement' => $product->getMesurement(),
                'productType' => $typeData['type']['name'],
                'productImage' => $typeData['images'][0]['url'],
                'productQuantity' => $cartItem->getQuantity(),
                'productTaxe' => $product->getTaxe(),
            ];
            $formattedCartItems[] = $productDetails;
        }

        return $this->json(['cartItems' => $formattedCartItems]);
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

        $typeData = [
            'id' => null,
            'name' => null,
            'parent' => null,
        ];

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

    #[Route('/api/update-cart', name: 'api_update_cart', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function updateCart(Request $request, ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();

        try {
            // Récupérer l'utilisateur actuellement authentifié
            $user = $this->getUser();

            // Récupérer les données du panier depuis la requête JSON
            $data = json_decode($request->getContent(), true);

            // Récupérer le panier de l'utilisateur
            $cart = $entityManager->getRepository(Cart::class)->findOneBy(['user' => $user]);

            if (!$cart) {
                // Créer un panier
                $cart = new Cart();
                // $cart->setCreatedAt(new \DateTime());
                $cart->setUser($user);
            }

            // Mise à jour de la date
            $cart->setCreatedAt(new \DateTime());
            $entityManager->persist($cart);

            foreach ($data['products'] as $productData) {
                $productId = $productData['productId'];

                $quantity = $productData['quantity'] ?? null;
                $newQuantity = $productData['newQuantity'] ?? null;


                $product = $entityManager->getRepository(Product::class)->find($productId);
                if ($product) {
                    // Vérifier si une ligne pour ce produit existe déjà dans le panier
                    $existingLineItem = $entityManager->getRepository(LineCart::class)
                        ->findOneBy(['cart' => $cart, 'product' => $product]);

                    if ($existingLineItem) {
                        // Si la ligne existe, mettez à jour la quantité
                        if (isset($quantity)) {
                            $existingLineItem->setQuantity($existingLineItem->getQuantity() + $quantity);
                        } elseif (isset($newQuantity)) {
                            $existingLineItem->setQuantity($newQuantity);
                        }
                    } else {
                        // Sinon, créez une nouvelle ligne au panier avec le produit et la quantité
                        $lineItem = new LineCart();
                        $lineItem->setCart($cart);
                        $lineItem->setProduct($product);
                        $lineItem->setQuantity($quantity);

                        // Ajoutez la nouvelle ligne au panier
                        $entityManager->persist($lineItem);
                    }
                }
            }
            // // Enregistrez le panier et les modifications dans la base de données
            $entityManager->flush();

            return new JsonResponse(['message' => 'Panier mis à jour avec succès !']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Une erreur s\'est produite lors de la mise à jour du panier.']);
        } catch (OptimisticLockException $e) {
            return new JsonResponse(['error' => 'Une erreur de verrouillage optimiste s\'est produite lors de la mise à jour du panier.']);
        }
    }

    #[Route('/api/delete-product-cart', name: 'api_delete_product_cart', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteProductFromCart(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        try {
            // Récupérer l'utilisateur actuellement authentifié
            $user = $this->getUser();

            // Récupérer les données du produit à supprimer depuis la requête JSON
            $data = json_decode($request->getContent(), true);

            // Récupérer le panier de l'utilisateur
            $cart = $entityManager->getRepository(Cart::class)->findOneBy(['user' => $user]);

            if ($cart) {
                // Récupérer le produit à partir de l'ID
                $productId = $data['productId'];
                $product = $entityManager->getRepository(Product::class)->find($productId);

                if ($product) {
                    // Recherchez et supprimez la ligne correspondante du panier
                    $lineItem = $entityManager->getRepository(LineCart::class)
                        ->findOneBy(['cart' => $cart, 'product' => $product]);

                    if ($lineItem) {
                        $entityManager->remove($lineItem);
                        $entityManager->flush();

                        return new JsonResponse(['message' => 'Produit supprimé du panier avec succès !']);
                    }
                }
            }

            return new JsonResponse(['error' => 'Produit non trouvé dans le panier.']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Une erreur s\'est produite lors de la suppression du produit du panier.']);
        }
    }
    
    #[Route('/api/clear-cart', name: 'api_clear_cart', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function clearCart(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        try {
            // Get the currently authenticated user
            $user = $this->getUser();

            // Get the user's cart
            $cart = $entityManager->getRepository(Cart::class)->findOneBy(['user' => $user]);

            if ($cart) {
                // Find and remove all line items associated with the user's cart
                $cartItems = $entityManager->getRepository(LineCart::class)->findBy(['cart' => $cart]);

                foreach ($cartItems as $lineItem) {
                    $entityManager->remove($lineItem);
                }

                $entityManager->flush();

                return new JsonResponse(['message' => 'Panier vidé avec succès !']);
            }

            return new JsonResponse(['error' => 'Panier non trouvé pour l\'utilisateur.']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Une erreur s\'est produite lors de la suppression des éléments du panier.']);
        }
    }
}
