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
            $numberOfReviews = mt_rand(0, 10);

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
        // Tableau de notes, titres et de commentaires prédéfinis
        $ratingsTitlesAndComments = [
            // [1, 'Déçu', 'Produit médiocre. Je ne suis pas du tout satisfait de l\'achat. La qualité est bien en dessous de mes attentes, et je ne recommanderais pas ce produit à d\'autres.'],
            // [2, 'Pas terrible', 'Je suis plutôt déçu par ce produit. Il ne répond pas vraiment à mes besoins, et je m\'attendais à quelque chose de mieux. La qualité est moyenne, et je ne pense pas le racheter.'],
            [3, 'Moyen', 'Le produit est tout juste moyen. Il remplit sa fonction de base, mais il n\'a rien d\'extraordinaire. J\'ai connu des produits similaires qui étaient meilleurs.'],
            [4, 'Super produit', 'Très bon produit ! Il répond parfaitement à mes attentes, et je le recommande vivement. La qualité est au rendez-vous, et j\'en suis très satisfait.'],
            [4, 'Excellent choix', 'Au-delà de mes attentes ! J\'ai été agréablement surpris par la qualité de ce produit. Il fait exactement ce qu\'il promet, et je le recommande sans hésitation.'],
            [4, "Très satisfait", "Ce produit a vraiment fait la différence pour mes cheveux. J'ai remarqué une amélioration significative de leur texture et de leur brillance. Très content de mon achat."],
            [5, "Bonne découverte", "Ce masque capillaire est une belle découverte. Il a aidé à revitaliser mes cheveux sans les alourdir. Je suis content des résultats obtenus."],
            [5, "Excellent choix", "Je suis agréablement surpris par la qualité de ce produit. Il a répondu à mes attentes et a laissé mes cheveux plus doux et plus sains. Je le recommande vivement à ceux qui cherchent un produit de qualité."],
            [5, "Très belle surprise", "Ce masque a été une révélation pour mes cheveux. Je l'utilise régulièrement et mes cheveux sont maintenant plus sains et plus beaux que jamais. Je ne peux plus m'en passer !"],
            [5, "Excellent produit", "Ce masque a une texture onctueuse qui est facile à appliquer. Après l'avoir utilisé, mes cheveux étaient incroyablement soyeux. Je suis ravie des résultats, et il sent très bon !"],
            [5, "Miracle", "Honnêtement, j'ai essayé de nombreux masques capillaires, mais celui-ci est devenu mon préféré. Il a complètement transformé la santé de mes cheveux. Ils sont moins cassants et beaucoup plus faciles à coiffer. Je suis fan !"],
            [5, 'J\'adore', "J'adore ce produit ! Mes cheveux étaient devenus très secs et ternes, mais après quelques utilisations, ils sont devenus super doux et brillants. L'odeur est divine, et la texture du masque est facile à rincer. Je recommande vivement ce produit pour des cheveux hydratés et en meilleure santé.."],
        ];
        // Sélectionnez aléatoirement une entrée dans le tableau
        $randomIndex = array_rand($ratingsTitlesAndComments);
        $ratingTitleAndComment = $ratingsTitlesAndComments[$randomIndex];
        $review->setRating($ratingTitleAndComment[0]); // Note
        $review->setTitle($ratingTitleAndComment[1]); // Titre
        $review->setComment($ratingTitleAndComment[2]); // Commentaire
        // Génération d'une date aléatoire dans une fourchette de 3 mois avant la date actuelle
        $startDate = new \DateTime('-3 months');
        $endDate = new \DateTime();
        $randomDate = mt_rand($startDate->getTimestamp(), $endDate->getTimestamp());
        $randomDateTime = new \DateTime();
        $randomDateTime->setTimestamp($randomDate);
        $review->setCreatedAt($randomDateTime);

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
