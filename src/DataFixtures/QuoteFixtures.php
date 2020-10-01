<?php

namespace App\DataFixtures;

use App\Entity\Quote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $quote = new Quote();
        $quote->setContent('CUUUUUUUUUILLÈÈÈÈÈÈRE !');
        $quote->setMeta('Le Burgonde.');
        $quote->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE_1));
        $quote->setAuthor($this->getReference(UserFixtures::USER_REFERENCE_1));
        $manager->persist($quote);

        $quote1 = new Quote();
        $quote1->setContent('Ce n’est pas les idées qui vous manquent… c’est la conviction de les réaliser.');
        $quote1->setMeta('Léodagan à Arthur.');
        $quote1->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE_1));
        $quote1->setAuthor($this->getReference(UserFixtures::USER_REFERENCE_1));
        $manager->persist($quote1);

        $quote2 = new Quote();
        $quote2->setContent('PAYS DE GALLE INDÉPENDAAAAAAAAAAAAAAAAAAAANT.');
        $quote2->setMeta('Perceval.');
        $quote2->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE_1));
        $quote2->setAuthor($this->getReference(UserFixtures::USER_REFERENCE_1));
        $manager->persist($quote2);

        $quote3 = new Quote();
        $quote3->setContent('Moi j’ai appris à lire, ben je souhaite ça à personne.');
        $quote3->setMeta('Léodagan.');
        $quote3->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE_1));
        $quote3->setAuthor($this->getReference(UserFixtures::USER_REFERENCE_2));
        $manager->persist($quote3);

        $quote4 = new Quote();
        $quote4->setContent('Si vous prenez aujourd’hui, que vous comptez sept jours, on retombe le même jour mais une semaine plus tard… Enfin à une vache près, c’est pas une science exacte.');
        $quote4->setMeta('Karadoc.');
        $quote4->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE_1));
        $quote4->setAuthor($this->getReference(UserFixtures::USER_REFERENCE_2));
        $manager->persist($quote4);

        $quote5 = new Quote();
        $quote5->setContent('Le whisky est une bonne eau à l’épreuve. vous dit qui est réel et qui ne l’est pas.');
        $quote5->setMeta('Tommy Shelby.');
        $quote5->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE_2));
        $quote5->setAuthor($this->getReference(UserFixtures::USER_REFERENCE_2));
        $manager->persist($quote5);

        $quote6 = new Quote();
        $quote6->setContent('Les hommes et leurs bites ne cessent de m’étonner.');
        $quote6->setMeta('Tante Polly');
        $quote6->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE_2));
        $quote6->setAuthor($this->getReference(UserFixtures::USER_REFERENCE_2));
        $manager->persist($quote6);

        $quote7 = new Quote();
        $quote7->setContent('Monsieur, avec le plus grand respect…. Thomas Shelby est un assassin, un assassin, un coupe-gorge, un bâtard, un bâtard, un gangster.');
        $quote7->setMeta('Inspecteur Campbell');
        $quote7->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE_2));
        $quote7->setAuthor($this->getReference(UserFixtures::USER_REFERENCE_2));
        $manager->persist($quote7);

        $quote8 = new Quote();
        $quote8->setContent('Tout le monde est une pute, Grace. Nous vendons juste différentes parties de nous-mêmes.');
        $quote8->setMeta('Tommy Shelby');
        $quote8->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE_2));
        $quote8->setAuthor($this->getReference(UserFixtures::USER_REFERENCE_2));
        $manager->persist($quote8);

        $quote9 = new Quote();
        $quote9->setContent('Dans le monde entier, les hommes violents sont les plus faciles à gérer.');
        $quote9->setMeta('Irene O’Donnell');
        $quote9->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE_2));
        $quote9->setAuthor($this->getReference(UserFixtures::USER_REFERENCE_2));
        $manager->persist($quote9);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          CategoryFixtures::class,
          UserFixtures::class,
        ];
    }
}
