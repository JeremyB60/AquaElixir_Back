<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Stripe\Stripe;
use Stripe\Product as StripeProduct;
// use Stripe\Price; /* Autre façon : je l'inclus directement dans le price::create plus bas */

class ProductFixtures extends Fixture implements DependentFixtureInterface
{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(); // Crée une instance de Faker
        Stripe::setApiKey('sk_test_51ONM1hBLaSzPsyD6VOMVr9rbdjCdxGRVvSVyO8jVBibfusIAWFYugYjzsKRLbvzn3bGYNfEAQFecZ5K3VQsQXtOh00Y0evnvfy');

        $productData = [
            'Anti-âge' => [
                'Sérum anti-âge',
                'Crème hydratante anti-âge',
                'Crème contour des yeux anti-rides',
                'Sérum éclaircissant',
            ],
            'Peau sensible' => [
                'Crème hydratante pour peau sensible',
                'Gel apaisant pour peau sensible',
            ],
            'Nettoyant' => [
                'Gel douche hydratant',
                'Gel douche énergisant',
                'Savon exfoliant au sel de mer et concombre de mer',
                'Lotion corporelle tonifiante',
                'Gommage exfoliant pour le corps',
            ],
            'Bain' => [
                'Sel de bain relaxant aux extraits de concombre de mer',
                'Bombe de bain relaxante',
            ],
            'Shampoing et après-shampoing' => [
                'Shampoing fortifiant et nourrissant',
                'Shampoing antipelliculaire',
                'Après-shampoing fortifiant et hydratant',
            ],
            'Cuir chevelu sensible' => [
                'Shampoing pour cuir chevelu sensible',
                'Après-shampoing pour cuir chevelu sensible',
            ],
            'Soin quotidien' => [
                'Crème de jour hydratante légère',
                'Crème de nuit régénérante',
                'Spray hydratant',
                'Lotion tonique apaisante',
                'Crème contour des yeux cernés et poches',
                'Masque purifiant et revitalisant',
                'Eau micellaire démaquillante',
                'Baume après-rasage hydratant',
                'Baume à lèvres hydratant',
            ],
            'Masque et coiffant' => [
                'Masque réparateur',
                'Masque hydratant',
                'Sérum capillaire revitalisant',
            ],
            'Peau, cheveux et ongles' => [
                'Complément alimentaire marin multivitaminé',
                'Capsules de collagène marin au concombre de mer',
                'Gélules régénérantes pour les cheveux et les ongles',
                'Complément beauté et bien-être',
                'Gélules d\'oméga-3 au concombre de mer',
            ],
            'Tisane, thé et infusion' => [
                'Tisanes relaxantes',
                'Thé digestif',
            ],
            'Digestion et transit' => [
                'Probiotiques pour la flore intestinale',
                'Capsules anti-inflammatoires',
            ],
            'Peau et cheveux' => [
                'Traitement anti-acné',
                'Crème anti-tâches brunes',
                'Crème apaisante pour l\'eczéma et le psoriasis',
                'Shampoing ultra apaisant pour cheveux sec',
                'Crème réparatrice pour les pieds',
                'Baume à lèvres réparateur',
            ],
            'Immunité' => [
                'Elixir marin pour renforcer le système immunitaire',
                'Spray nasal à l\'eau de mer et extraits de concombre de mer',
                'Sirop apaisant pour les maux de gorge',
                // 'Gelée de concombre de mer',
                // 'Poudre de concombre de mer',
            ],
            'Protection solaire' => [
                'Crème solaire SPF 30',
                'Crème solaire SPF 50',
                'Lotion après-soleil apaisante',
            ],
            'Coffret cadeau' => [
                'Coffret'
            ],
        ];

        foreach ($productData as $categoryName => $products) {
            $categoryType = $this->getReference('type_' . $this->slugify($categoryName));

            foreach ($products as $productName) {
                $product = new Product();
                $product->setName($productName);
                $product->setReference('REF-' . uniqid(true));
                $price = ($faker->numberBetween(1, 5) . '9.99');
                $product->setPrice($price);
                $product->setTaxe($price * 0.2 / 1.2);
                $product->setDescription($this->generateRandomDescription());
                $product->setDetailedDescription($this->generateRandomDetailedDescription());
                $product->setMesurement($faker->randomElement(['50 ml', '100 ml', '200ml']));
                $product->setStock($faker->numberBetween(2, 5) * 10);
                $product->setSlug($this->slugify($productName));
                $product->setProductType($categoryType);
                // Générer une date aléatoire sur une période d'un mois
                $randomDays = mt_rand(0, 30); // Nombre de jours aléatoire entre 0 et 30
                $randomDate = new \DateTime("-$randomDays days");
                $product->setCreatedAt($randomDate);
                $this->addReference('product_' . $this->slugify($productName), $product);
                $manager->persist($product);
                // Creation du produit sur Stripe
                $stripeProduct = StripeProduct::create([
                    'name' => $product->getName(),
                    'description' => $product->getDescription(),
                    // 'images' => ['https://localhost:8000/public/images/products/' . $this->slugify($productName) . '.jpg'],
                ]);
                // Associer l'ID du produit Stripe avec le produit de la bdd
                $product->setStripeProductId($stripeProduct->id);

                // Ajout du prix en centimes sur Stripe
                $stripePrice = \Stripe\Price::create([
                    'product' => $stripeProduct->id,
                    'unit_amount' => $price * 100, // Montant en centimes
                    'currency' => 'EUR',
                ]);
                // Associer le prix du produit Stripe avec le prix de la bdd
                $product->setStripePriceId($stripePrice->id);
            }
        }

        $manager->flush();
    }

    private function generateRandomDescription(): string
    {
        $descriptions = [
            "Formule hydratante apaisant à l'aloès",
            "Composition purifiante à l'argile",
            "Préparation anti-âge rajeunissant",
            "Produit nettoyant éclat quotidien",
            "Substance nourrissante pour peau",
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function generateRandomDetailedDescription(): string
    {
        $detailedDescriptions = [
            "Produit hydratant apaisant à l'aloès. Une formule légère qui nourrit
             la peau en profondeur, la laissant douce et revitalisée. Un must pour une hydratation quotidienne.",
            "Produit purifiant à l'argile. Élimine efficacement les impuretés tout en apaisant la peau.
             Un rituel de soin hebdomadaire pour une peau nette et éclatante.",
            "Produit rajeunissant. Une puissante combinaison d'ingrédients actifs qui ciblent les signes du
             vieillissement. Obtenez une peau plus ferme et plus jeune.",
            "Produit nettoyant éclat quotidien. Nettoie en douceur tout en révélant l'éclat naturel de la peau.
             Une sensation de fraîcheur pour commencer chaque journée.",
            "Produit nourrissant pour peau. Une formule riche en nutriments qui hydrate intensément,
             laissant la peau douce et souple. L'alliée parfaite pour une peau bien nourrie.",
        ];



        return $detailedDescriptions[array_rand($detailedDescriptions)];
    }

    private function slugify(string $text): string
    {
        return $this->slugger->slug($text)->lower()->toString();
    }

    public function getDependencies(): array
    {
        // Passe ProductFixtures avant ImageFixtures
        return [
            TypeFixtures::class
        ];
    }
}
