<?php

namespace App\DataFixtures;

use App\Entity\Type;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $type = new Type();
        $type->setTypeName("Soin capillaire");
        $manager->persist($type);

        //fixtures pour les soins capillaires---------------------------------------------------------------------------------------

        $product = new Product();
        $product->setName("Shampoing fortifiant et nourrissant");
        $product->setReference("Ref" . 1);
        $product->setProductType($type);
        $product->setPrice(1);
        $product->setTaxe(1);
        $product->setDescription("Description " . 1);
        $product->setDetailedDescription("Description détaillée " . 1);
        $product->setMesurement("Dosage " . 1);
        $product->setStock(1);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Shampoing antipelliculaire");
        $product->setReference("Ref" . 2);
        $product->setProductType($type);
        $product->setPrice(2);
        $product->setTaxe(2);
        $product->setDescription("Description " . 2);
        $product->setDetailedDescription("Description détaillée " . 2);
        $product->setMesurement("Dosage " . 2);
        $product->setStock(2);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Shampoing pour cuir chevelu sensible");
        $product->setReference("Ref" . 3);
        $product->setProductType($type);
        $product->setPrice(3);
        $product->setTaxe(3);
        $product->setDescription("Description " . 3);
        $product->setDetailedDescription("Description détaillée " . 3);
        $product->setMesurement("Dosage " . 3);
        $product->setStock(3);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Après-shampoing fortifiant et hydratant");
        $product->setReference("Ref" . 4);
        $product->setProductType($type);
        $product->setPrice(4);
        $product->setTaxe(4);
        $product->setDescription("Description " . 4);
        $product->setDetailedDescription("Description détaillée " . 4);
        $product->setMesurement("Dosage " . 4);
        $product->setStock(4);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Après-shampoing pour cuir chevelu sensible");
        $product->setReference("Ref" . 5);
        $product->setProductType($type);
        $product->setPrice(5);
        $product->setTaxe(5);
        $product->setDescription("Description " . 5);
        $product->setDetailedDescription("Description détaillée " . 5);
        $product->setMesurement("Dosage " . 5);
        $product->setStock(5);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Masque réparateur");
        $product->setReference("Ref" . 6);
        $product->setProductType($type);
        $product->setPrice(6);
        $product->setTaxe(6);
        $product->setDescription("Description " . 6);
        $product->setDetailedDescription("Description détaillée " . 6);
        $product->setMesurement("Dosage " . 6);
        $product->setStock(6);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Masque hydratant");
        $product->setReference("Ref" . 7);
        $product->setProductType($type);
        $product->setPrice(7);
        $product->setTaxe(7);
        $product->setDescription("Description " . 7);
        $product->setDetailedDescription("Description détaillée " . 7);
        $product->setMesurement("Dosage " . 7);
        $product->setStock(7);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Sérum capillaire revitalisant");
        $product->setReference("Ref" . 8);
        $product->setProductType($type);
        $product->setPrice(8);
        $product->setTaxe(8);
        $product->setDescription("Description " . 8);
        $product->setDetailedDescription("Description détaillée " . 8);
        $product->setMesurement("Dosage " . 8);
        $product->setStock(8);
        $manager->persist($product);

        //fixtures pour les soins du visage------------------------------------------------------------------------------------------

        $type = new Type();
        $type->setTypeName("Soin du visage");
        $manager->persist($type);
        
        $product = new Product();
        $product->setName("Sérum anti-âge");
        $product->setReference("Ref" . 9);
        $product->setProductType($type);
        $product->setPrice(9);
        $product->setTaxe(9);
        $product->setDescription("Description " . 9);
        $product->setDetailedDescription("Description détaillée " . 9);
        $product->setMesurement("Dosage " . 9);
        $product->setStock(9);
        $manager->persist($product);
        
        $product = new Product();
        $product->setName("Sérum éclaircissant");
        $product->setReference("Ref" . 10);
        $product->setProductType($type);
        $product->setPrice(10);
        $product->setTaxe(10);
        $product->setDescription("Description " . 10);
        $product->setDetailedDescription("Description détaillée " . 10);
        $product->setMesurement("Dosage " . 10);
        $product->setStock(10);
        $manager->persist($product);
        
        $product = new Product();
        $product->setName("Crême hydratante anti-âge");
        $product->setReference("Ref" . 11);
        $product->setProductType($type);
        $product->setPrice(11);
        $product->setTaxe(11);
        $product->setDescription("Description " . 11);
        $product->setDetailedDescription("Description détaillée " . 11);
        $product->setMesurement("Dosage " . 11);
        $product->setStock(11);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Crème hydratante pour peau sensible");
        $product->setReference("Ref" . 12);
        $product->setProductType($type);
        $product->setPrice(12);
        $product->setTaxe(12);
        $product->setDescription("Description " . 12);
        $product->setDetailedDescription("Description détaillée " . 12);
        $product->setMesurement("Dosage " . 12);
        $product->setStock(12);
        $manager->persist($product);
        
        $product = new Product();
        $product->setName("Crème de jour hydratante légère (usage quotidien)");
        $product->setReference("Ref" . 13);
        $product->setProductType($type);
        $product->setPrice(13);
        $product->setTaxe(13);
        $product->setDescription("Description " . 13);
        $product->setDetailedDescription("Description détaillée " . 13);
        $product->setMesurement("Dosage " . 13);
        $product->setStock(13);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Crème de nuit régénèrante");
        $product->setReference("Ref" . 14);
        $product->setProductType($type);
        $product->setPrice(14);
        $product->setTaxe(14);
        $product->setDescription("Description " . 14);
        $product->setDetailedDescription("Description détaillée " . 14);
        $product->setMesurement("Dosage " . 14);
        $product->setStock(14);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Spray hydratant");
        $product->setReference("Ref" . 15);
        $product->setProductType($type);
        $product->setPrice(15);
        $product->setTaxe(15);
        $product->setDescription("Description " . 15);
        $product->setDetailedDescription("Description détaillée " . 15);
        $product->setMesurement("Dosage " . 15);
        $product->setStock(15);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Lotion tonique appaisante");
        $product->setReference("Ref" . 16);
        $product->setProductType($type);
        $product->setPrice(16);
        $product->setTaxe(16);
        $product->setDescription("Description " . 16);
        $product->setDetailedDescription("Description détaillée " . 16);
        $product->setMesurement("Dosage " . 16);
        $product->setStock(16);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Crème contour des yeux cernes et poches");
        $product->setReference("Ref" . 17);
        $product->setProductType($type);
        $product->setPrice(17);
        $product->setTaxe(17);
        $product->setDescription("Description " . 17);
        $product->setDetailedDescription("Description détaillée " . 17);
        $product->setMesurement("Dosage " . 17);
        $product->setStock(17);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Crème contour des yeux anti-rides");
        $product->setReference("Ref" . 18);
        $product->setProductType($type);
        $product->setPrice(18);
        $product->setTaxe(18);
        $product->setDescription("Description " . 18);
        $product->setDetailedDescription("Description détaillée " . 18);
        $product->setMesurement("Dosage " . 18);
        $product->setStock(18);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Masque purifiant et revitalisant");
        $product->setReference("Ref" . 19);
        $product->setProductType($type);
        $product->setPrice(19);
        $product->setTaxe(19);
        $product->setDescription("Description " . 19);
        $product->setDetailedDescription("Description détaillée " . 19);
        $product->setMesurement("Dosage " . 19);
        $product->setStock(19);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Gel nettoyant doux");
        $product->setReference("Ref" . 20);
        $product->setProductType($type);
        $product->setPrice(20);
        $product->setTaxe(20);
        $product->setDescription("Description " . 20);
        $product->setDetailedDescription("Description détaillée " . 20);
        $product->setMesurement("Dosage " . 20);
        $product->setStock(20);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Gel apaisant pour peau sensible");
        $product->setReference("Ref" . 21);
        $product->setProductType($type);
        $product->setPrice(21);
        $product->setTaxe(21);
        $product->setDescription("Description " . 21);
        $product->setDetailedDescription("Description détaillée " . 21);
        $product->setMesurement("Dosage " . 21);
        $product->setStock(21);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Eau micellaire démaquillante");
        $product->setReference("Ref" . 22);
        $product->setProductType($type);
        $product->setPrice(22);
        $product->setTaxe(22);
        $product->setDescription("Description " . 22);
        $product->setDetailedDescription("Description détaillée " . 22);
        $product->setMesurement("Dosage " . 22);
        $product->setStock(22);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Baume après-rasage hydratant");
        $product->setReference("Ref" . 23);
        $product->setProductType($type);
        $product->setPrice(23);
        $product->setTaxe(23);
        $product->setDescription("Description " . 23);
        $product->setDetailedDescription("Description détaillée " . 23);
        $product->setMesurement("Dosage " . 23);
        $product->setStock(23);
        $manager->persist($product);        

        $product = new Product();
        $product->setName("Baume à lèvres hydratant");
        $product->setReference("Ref" . 24);
        $product->setProductType($type);
        $product->setPrice(24);
        $product->setTaxe(24);
        $product->setDescription("Description " . 24);
        $product->setDetailedDescription("Description détaillée " . 24);
        $product->setMesurement("Dosage " . 24);
        $product->setStock(24);
        $manager->persist($product); 
        
        // Fixtures pour les Soins corporels---------------------------------------------------------------------------------------------
        $type = new Type();
        $type->setTypeName("Soin corporel");
        $manager->persist($type);

        $product = new Product();
        $product->setName("Gel douche hydratant");
        $product->setReference("Ref" . 25);
        $product->setProductType($type);
        $product->setPrice(25);
        $product->setTaxe(25);
        $product->setDescription("Description " . 25);
        $product->setDetailedDescription("Description détaillée " . 25);
        $product->setMesurement("Dosage " . 25);
        $product->setStock(25);
        $manager->persist($product);

        $product = new Product();
        $product->setName(" Gel douche énergisant");
        $product->setReference("Ref" . 26);
        $product->setProductType($type);
        $product->setPrice(26);
        $product->setTaxe(26);
        $product->setDescription("Description " . 26);
        $product->setDetailedDescription("Description détaillée " . 26);
        $product->setMesurement("Dosage " . 26);
        $product->setStock(26);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Savon e.foliant au sel de mer et concombre de mer");
        $product->setReference("Ref" . 27);
        $product->setProductType($type);
        $product->setPrice(27);
        $product->setTaxe(27);
        $product->setDescription("Description " . 27);
        $product->setDetailedDescription("Description détaillée " . 27);
        $product->setMesurement("Dosage " . 27);
        $product->setStock(27);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Lotion corporelle tonifiante");
        $product->setReference("Ref" . 28);
        $product->setProductType($type);
        $product->setPrice(28);
        $product->setTaxe(28);
        $product->setDescription("Description " . 28);
        $product->setDetailedDescription("Description détaillée " . 28);
        $product->setMesurement("Dosage " . 28);
        $product->setStock(28);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Gommage exfoliant pour le corps");
        $product->setReference("Ref" . 29);
        $product->setProductType($type);
        $product->setPrice(29);
        $product->setTaxe(29);
        $product->setDescription("Description " . 29);
        $product->setDetailedDescription("Description détaillée " . 29);
        $product->setMesurement("Dosage " . 29);
        $product->setStock(29);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Huile de massage apaisante");
        $product->setReference("Ref" . 30);
        $product->setProductType($type);
        $product->setPrice(30);
        $product->setTaxe(30);
        $product->setDescription("Description " . 30);
        $product->setDetailedDescription("Description détaillée " . 30);
        $product->setMesurement("Dosage " . 30);
        $product->setStock(30);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Sel de bain relaxant aux extraits de concombres de mer");
        $product->setReference("Ref" . 31);
        $product->setProductType($type);
        $product->setPrice(31);
        $product->setTaxe(31);
        $product->setDescription("Description " . 31);
        $product->setDetailedDescription("Description détaillée " . 31);
        $product->setMesurement("Dosage " . 31);
        $product->setStock(31);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Bombe de bain relaxante");
        $product->setReference("Ref" . 32);
        $product->setProductType($type);
        $product->setPrice(32);
        $product->setTaxe(32);
        $product->setDescription("Description " . 32);
        $product->setDetailedDescription("Description détaillée " . 32);
        $product->setMesurement("Dosage " . 32);
        $product->setStock(32);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Crème pour les mains");
        $product->setReference("Ref" . 33);
        $product->setProductType($type);
        $product->setPrice(33);
        $product->setTaxe(33);
        $product->setDescription("Description " . 33);
        $product->setDetailedDescription("Description détaillée " . 33);
        $product->setMesurement("Dosage " . 33);
        $product->setStock(33);
        $manager->persist($product);
        
        //fixtures pour lea Parapharmacie-------------------------------------------------------------------------------------------

        $type = new Type();
        $type->setTypeName("Parapharmacie");
        $manager->persist($type);
        
        $product = new Product();
        $product->setName("Taitement anti-acné");
        $product->setReference("Ref" . 34);
        $product->setProductType($type);
        $product->setPrice(34);
        $product->setTaxe(34);
        $product->setDescription("Description " . 34);
        $product->setDetailedDescription("Description détaillée " . 34);
        $product->setMesurement("Dosage " . 34);
        $product->setStock(34);
        $manager->persist($product); 
           
        $product = new Product();
        $product->setName("Crème anti-taches brunes");
        $product->setReference("Ref" . 35);
        $product->setProductType($type);
        $product->setPrice(35);
        $product->setTaxe(35);
        $product->setDescription("Description " . 35);
        $product->setDetailedDescription("Description détaillée " . 35);
        $product->setMesurement("Dosage " . 35);
        $product->setStock(35);
        $manager->persist($product); 
           
        $product = new Product();
        $product->setName("Crème apaisante pour l'eczéma et le psoriasis");
        $product->setReference("Ref" . 36);
        $product->setProductType($type);
        $product->setPrice(36);
        $product->setTaxe(36);
        $product->setDescription("Description " . 36);
        $product->setDetailedDescription("Description détaillée " . 36);
        $product->setMesurement("Dosage " . 36);
        $product->setStock(36);
        $manager->persist($product);             
           
        $product = new Product();
        $product->setName("Shampoing ultra apaisant pour cheveux secs");
        $product->setReference("Ref" . 37);
        $product->setProductType($type);
        $product->setPrice(37);
        $product->setTaxe(37);
        $product->setDescription("Description " . 37);
        $product->setDetailedDescription("Description détaillée " . 37);
        $product->setMesurement("Dosage " . 37);
        $product->setStock(37);
        $manager->persist($product);    
           
        $product = new Product();
        $product->setName("Crème réparatrice pour les pied");
        $product->setReference("Ref" . 38);
        $product->setProductType($type);
        $product->setPrice(38);
        $product->setTaxe(38);
        $product->setDescription("Description " . 38);
        $product->setDetailedDescription("Description détaillée " . 38);
        $product->setMesurement("Dosage " . 38);
        $product->setStock(38);
        $manager->persist($product);    
           
        $product = new Product();
        $product->setName("Gelée de concombres de mer");
        $product->setReference("Ref" . 39);
        $product->setProductType($type);
        $product->setPrice(39);
        $product->setTaxe(39);
        $product->setDescription("Description " . 39);
        $product->setDetailedDescription("Description détaillée " . 39);
        $product->setMesurement("Dosage " . 39);
        $product->setStock(39);
        $manager->persist($product);       
           
        $product = new Product();
        $product->setName("Spray nasal à l’eau de mer et extraits de concombres de mer");
        $product->setReference("Ref" . 40);
        $product->setProductType($type);
        $product->setPrice(40);
        $product->setTaxe(40);
        $product->setDescription("Description " . 40);
        $product->setDetailedDescription("Description détaillée " . 40);
        $product->setMesurement("Dosage " . 40);
        $product->setStock(40);
        $manager->persist($product);     
           
        $product = new Product();
        $product->setName("Baume à lèvres réparateur");
        $product->setReference("Ref" . 41);
        $product->setProductType($type);
        $product->setPrice(41);
        $product->setTaxe(41);
        $product->setDescription("Description " . 41);
        $product->setDetailedDescription("Description détaillée " . 41);
        $product->setMesurement("Dosage " . 41);
        $product->setStock(41);
        $manager->persist($product);           
           
        $product = new Product();
        $product->setName("Elixir marin pour renforcer le système immunitaire");
        $product->setReference("Ref" . 42);
        $product->setProductType($type);
        $product->setPrice(42);
        $product->setTaxe(42);
        $product->setDescription("Description " . 42);
        $product->setDetailedDescription("Description détaillée " . 42);
        $product->setMesurement("Dosage " . 42);
        $product->setStock(41);
        $manager->persist($product);          
           
        $product = new Product();
        $product->setName("Sirop apaisant pour les maux de gorge");
        $product->setReference("Ref" . 43);
        $product->setProductType($type);
        $product->setPrice(43);
        $product->setTaxe(43);
        $product->setDescription("Description " . 43);
        $product->setDetailedDescription("Description détaillée " . 43);
        $product->setMesurement("Dosage " . 43);
        $product->setStock(43);
        $manager->persist($product);  
           
        $product = new Product();
        $product->setName("Probiotiques pour la flore intestinale");
        $product->setReference("Ref" . 44);
        $product->setProductType($type);
        $product->setPrice(44);
        $product->setTaxe(44);
        $product->setDescription("Description " . 44);
        $product->setDetailedDescription("Description détaillée " . 44);
        $product->setMesurement("Dosage " . 44);
        $product->setStock(44);
        $manager->persist($product); 
        
        
        // Fixtures pour la Protection solaire--------------------------------------------------------------------------------------
        
        $type = new Type();
        $type->setTypeName("Protection solaire");
        $manager->persist($type);

        $product = new Product();
        $product->setName("Crème solaire SPF 30");
        $product->setReference("Ref" . 45);
        $product->setProductType($type);
        $product->setPrice(45);
        $product->setTaxe(45);
        $product->setDescription("Description " . 45);
        $product->setDetailedDescription("Description détaillée " . 45);
        $product->setMesurement("Dosage " . 45);
        $product->setStock(45);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Crème solaire SPF 50");
        $product->setReference("Ref" . 46);
        $product->setProductType($type);
        $product->setPrice(46);
        $product->setTaxe(46);
        $product->setDescription("Description " . 46);
        $product->setDetailedDescription("Description détaillée " . 46);
        $product->setMesurement("Dosage " . 46);
        $product->setStock(46);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Lotion après-soleil apaisante");
        $product->setReference("Ref" . 47);
        $product->setProductType($type);
        $product->setPrice(47);
        $product->setTaxe(47);
        $product->setDescription("Description " . 47);
        $product->setDetailedDescription("Description détaillée " . 47);
        $product->setMesurement("Dosage " . 47);
        $product->setStock(47);
        $manager->persist($product);
        
        // Fixtures pour les Compléments alimentaires----------------------------------------------------------------------------
        
        $type = new Type();
        $type->setTypeName("Compléments alimentaires");
        $manager->persist($type);

        $product = new Product();
        $product->setName("Complément alimentaire marin multivitaminé");
        $product->setReference("Ref" . 48);
        $product->setProductType($type);
        $product->setPrice(48);
        $product->setTaxe(48);
        $product->setDescription("Description " . 48);
        $product->setDetailedDescription("Description détaillée " . 48);
        $product->setMesurement("Dosage " . 48);
        $product->setStock(48);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Gélules d'oméga-3 au concombre de mer");
        $product->setReference("Ref" . 49);
        $product->setProductType($type);
        $product->setPrice(49);
        $product->setTaxe(49);
        $product->setDescription("Description " . 49);
        $product->setDetailedDescription("Description détaillée " . 49);
        $product->setMesurement("Dosage " . 49);
        $product->setStock(49);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Capsules anti-inflammatoires");
        $product->setReference("Ref" . 50);
        $product->setProductType($type);
        $product->setPrice(50);
        $product->setTaxe(50);
        $product->setDescription("Description " . 50);
        $product->setDetailedDescription("Description détaillée " . 50);
        $product->setMesurement("Dosage " . 50);
        $product->setStock(50);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Capsules de collagène marin au concombre de mer");
        $product->setReference("Ref" . 51);
        $product->setProductType($type);
        $product->setPrice(51);
        $product->setTaxe(51);
        $product->setDescription("Description " . 51);
        $product->setDetailedDescription("Description détaillée " . 51);
        $product->setMesurement("Dosage " . 51);
        $product->setStock(51);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Complément Beauté et Bien-Être");
        $product->setReference("Ref" . 52);
        $product->setProductType($type);
        $product->setPrice(52);
        $product->setTaxe(52);
        $product->setDescription("Description " . 52);
        $product->setDetailedDescription("Description détaillée " . 52);
        $product->setMesurement("Dosage " . 52);
        $product->setStock(52);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Gélules régénérantes pour les cheveu. et les ongles");
        $product->setReference("Ref" . 53);
        $product->setProductType($type);
        $product->setPrice(53);
        $product->setTaxe(53);
        $product->setDescription("Description " . 53);
        $product->setDetailedDescription("Description détaillée " . 53);
        $product->setMesurement("Dosage " . 53);
        $product->setStock(53);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Tisanes relaxantes");
        $product->setReference("Ref" . 54);
        $product->setProductType($type);
        $product->setPrice(54);
        $product->setTaxe(54);
        $product->setDescription("Description " . 54);
        $product->setDetailedDescription("Description détaillée " . 54);
        $product->setMesurement("Dosage " . 54);
        $product->setStock(54);
        $manager->persist($product);

        $product = new Product();
        $product->setName(" Thé digestif");
        $product->setReference("Ref" . 55);
        $product->setProductType($type);
        $product->setPrice(55);
        $product->setTaxe(55);
        $product->setDescription("Description " . 55);
        $product->setDetailedDescription("Description détaillée " . 55);
        $product->setMesurement("Dosage " . 55);
        $product->setStock(55);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Poudre de concombre de mer");
        $product->setReference("Ref" . 56);
        $product->setProductType($type);
        $product->setPrice(56);
        $product->setTaxe(56);
        $product->setDescription("Description " . 56);
        $product->setDetailedDescription("Description détaillée " . 56);
        $product->setMesurement("Dosage " . 56);
        $product->setStock(56);
        $manager->persist($product);

        
        $manager->flush();  
    }
}