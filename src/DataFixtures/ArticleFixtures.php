<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Article;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $article = new Article();

        $article->setTitle('test');
        $article->setDescription('test');
        $article->setMetaDescription('test');
        $article->setTitle('test');
        $article->setSubtitle('test');
        $article->setVisible(true);
        $article->setUser($this->getReference(UserFixtures::USER_REFERENCE));


        $manager->persist($article);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
