<?php

namespace App\DataFixtures;

use App\Domain\Article\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $article1 = new Article();
        $article1->setId(1)
            ->setName('Article1 name')
            ->setShortDescription('Article1 short description');

        $manager->persist($article1);
        $manager->flush();
    }
}
