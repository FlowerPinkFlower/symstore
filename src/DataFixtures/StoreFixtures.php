<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Produits;
use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class StoreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Génération des categories
        for($c=0; $c<5;$c++){
            $cat = new Categories();
            $cat->setName($faker->word());
            $manager->persist($cat);

            for($p=0; $p<mt_rand(1, 5); $p++){  //mt_rand permet de générer de manière aléatoire 1 et 5 articles de la categorie Article
                $prod = new Produits();
                $prod -> setName($faker->word(5,true));
                $prod -> setQuantity($faker->randomNumber(3,true));
                $prod -> setUnitPrice($faker->randomFloat(2,1,250));
                $prod -> setCategorie($cat); //création de la relation entre la table article et categorie
                $manager -> persist($prod);
            }
            $manager->flush();
        }
    }
}