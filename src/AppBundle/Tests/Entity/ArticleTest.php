<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\Article;

class ArticleTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 6;
    const NB_ERROR_UNIQUE = 1;
    const NO_ERROR = 0;

    public function setUp(){
      self::bootKernel();
      $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
      $this->validator = static::$kernel->getContainer()->get('validator');
    }

    /**
     * give complete and good entity
     */
     private function getGoldenPass(){
        $article = new Article;
        $article->setTitle('yolo');
        $article->setDescription('bla bla bla');
        $article->setMetaDescription('unique');
        $article->setSubtitle('unique');
        $article->setVisible('1');
        $article->setUser(
          $this->em->getRepository("AppBundle:User")->find(1)
        );

        return $article;
     }

     /**
      * golden pass
      */
      public function testGoldenPass(){
         $article = $this->getGoldenPass();
         $violationList = $this->validator->validate($article);
         $this->assertEquals($violationList->count(), self::NO_ERROR);
      }

      /**
       * The name field must be not null
       */
      public function testNotNullValue(){

        $article = new article;

        $violationList = $this->validator->validate($article);
        $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
      }

      /**
       * The name must be unique, "bitoin exists already"
       */
      public function testNameUnique(){
        $article = $this->getGoldenPass();
        $article->setTitle('unique');

        $violationList = $this->validator->validate($article);
        $this->assertEquals($violationList->count(), self::NB_ERROR_UNIQUE);
      }


}
