<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\CurrencyValueMoment;

class CurrencyValueMomentTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 2;
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
        $currencyValueMoment = new CurrencyValueMoment;
        $currencyValueMoment->setRank(1);
        $currencyValueMoment->setCurrency(
          $this->em->getRepository("AppBundle:Currency")->find(1)
        );

        return $currencyValueMoment;
     }

     /**
      * golden pass
      */
      public function testGoldenPass(){
         $currencyValueMoment = $this->getGoldenPass();
         $violationList = $this->validator->validate($currencyValueMoment);
         $this->assertEquals($violationList->count(), self::NO_ERROR);
      }
    /**
     * The name field must be not null
     */
    public function testNotNullValue(){

      $currencyValueMoment = new CurrencyValueMoment;

      $violationList = $this->validator->validate($currencyValueMoment);
      $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
