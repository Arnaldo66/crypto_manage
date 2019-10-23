<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $article = new Article();
        $article->setTitle('unique');
        $article->setDescription('unique');
        $article->setMetaDescription('unique');
        $article->setSubtitle('unique');
        $article->setVisible('1');
        $article->setUser($this->getReference('user'));

        $manager->persist($article);
        $manager->flush();
    }

    public function getOrder()
    {
        return 8;
    }
}
