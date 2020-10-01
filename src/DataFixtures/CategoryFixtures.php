<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_REFERENCE_1 = 'category1';
    public const CATEGORY_REFERENCE_2 = 'category2';

    public function load(ObjectManager $manager)
    {
        $category1 = new Category();
        $category1->setName('Kaamelott');
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('Peaky Blinders');
        $manager->persist($category2);

        $manager->flush();
        $this->addReference(self::CATEGORY_REFERENCE_1, $category1);
        $this->addReference(self::CATEGORY_REFERENCE_2, $category2);
    }
}
