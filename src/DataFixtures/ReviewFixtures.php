<?php

// src/DataFixtures/ReviewFixtures.php
namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $productData = [
            'Shampoing fortifiant et nourrissant',
            'Shampoing antipelliculaire',
            'Shampoing pour cuir chevelu sensible',
            'Après-shampoing fortifiant et hydratant',
            'Après-shampoing pour cuir chevelu sensible',
            'Masque réparateur',
            'Masque hydratant',
            'Sérum capillaire revitalisant',
            'Sérum anti-âge',
            'Sérum éclaircissant',
            'Crème hydratante anti-âge',
            'Crème hydratante pour peau sensible',
            'Crème de jour hydratante légère',
            'Crème de nuit régénérante',
            'Spray hydratant',
            'Lotion tonique apaisante',
            'Crème contour des yeux cernés et poches',
            'Crème contour des yeux anti-rides',
            'Masque purifiant et revitalisant',
            'Gel apaisant pour peau sensible',
            'Eau micellaire démaquillante',
            'Baume après-rasage hydratant',
            'Baume à lèvres hydratant',
            'Gel douche hydratant',
            'Gel douche énergisant',
            'Savon exfoliant au sel de mer et concombre de mer',
            'Lotion corporelle tonifiante',
            'Gommage exfoliant pour le corps',
            'Bombe de bain relaxante',
            'Traitement anti-acné',
            'Crème anti-tâches brunes',
            'Crème apaisante pour l\'eczéma et le psoriasis',
            'Shampoing ultra apaisant pour cheveux sec',
            'Crème réparatrice pour les pieds',
            'Gelée de concombre de mer',
            'Spray nasal à l\'eau de mer et extraits de concombre de mer',
            'Baume à lèvres réparateur',
            'Elixir marin pour renforcer le système immunitaire',
            'Sirop apaisant pour les maux de gorge',
            'Probiotiques pour la flore intestinale',
            'Crème solaire SPF 30',
            'Crème solaire SPF 50',
            'Lotion après-soleil apaisante',
            'Complément alimentaire marin multivitaminé',
            'Gélules d\'oméga-3 au concombre de mer',
            'Capsules anti-inflammatoires',
            'Capsules de collagène marin au concombre de mer',
            'Complément beauté et bien-être',
            'Gélules régénérantes pour les cheveux et les ongles',
            'Tisanes relaxantes',
            'Thé digestif',
            'Poudre de concombre de mer',
            'Coffret',
        ];
        // Récupérez la liste de produits à partir des références
        foreach ($productData as $productName) {
            $prodName = $this->getReference('product_' . $this->slugify($productName));
            // Générez un nombre aléatoire entre 0 et 6 pour déterminer le nombre de critiques à créer
            $numberOfReviews = mt_rand(0, 6);

            for ($i = 0; $i < $numberOfReviews; $i++) {
                $this->createReview($manager, $prodName);
            }
        }

        $manager->flush();
    }

    private function createReview(ObjectManager $manager, $product)
    {
        $review = new Review();
        $review->setProductReview($product);
        $userId = $this->getReference('user' . mt_rand(0, 20));
        $review->setReviewUser($userId);
        // Tableau de notes et de commentaires prédéfinis
        $ratingsTitlesAndComments = [
            [1, 'Déçu', 'Produit médiocre. Je ne suis pas satisfait de l\'achat.'],
            [2, 'Pas terrible', 'Pas vraiment convaincu par ce produit.'],
            [3, 'Moyen', 'Produit moyen. Rien d\'extraordinaire.'],
            [4, 'Super produit', 'Très bon produit ! Je le recommande vivement.'],
            [5, 'Top !', 'Excellent produit ! Au-delà de mes attentes.'],
        ];
        // Sélectionnez aléatoirement une entrée dans le tableau
        $randomIndex = array_rand($ratingsTitlesAndComments);
        $ratingTitleAndComment = $ratingsTitlesAndComments[$randomIndex];
        $review->setRating($ratingTitleAndComment[0]); // Note
        $review->setTitle($ratingTitleAndComment[1]); // Titre
        $review->setComment($ratingTitleAndComment[2]); // Commentaire
        $review->setCreatedAt(new \DateTime());

        $manager->persist($review);
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
        ];
    }

    private function slugify(string $text): string
    {
        return $this->slugger->slug($text)->lower()->toString();
    }
}
