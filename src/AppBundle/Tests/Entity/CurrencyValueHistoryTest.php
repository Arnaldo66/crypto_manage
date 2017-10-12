<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\CurrencyValueHistory;

class CurrencyValueHistoryTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 8;
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
        $currencyValueHistory = new CurrencyValueHistory;
        $currencyValueHistory->setHightUsd(1);
        $currencyValueHistory->setLowUsd(1);
        $currencyValueHistory->setHightEur(1);
        $currencyValueHistory->setLowEur(1);
        $currencyValueHistory->setAverageUsd(1);
        $currencyValueHistory->setAverageEur(1);
        $currencyValueHistory->setDay(new \DateTime());
        $currencyValueHistory->setCurrency(
          $this->em->getRepository("AppBundle:Currency")->find(1)
        );

        return $currencyValueHistory;
     }

     /**
      * golden pass
      */
      public function testGoldenPass(){
         $currencyValueHistory = $this->getGoldenPass();
         $violationList = $this->validator->validate($currencyValueHistory);
         $this->assertEquals($violationList->count(), self::NO_ERROR);
      }

    /**
     * The name field must be not null
     */
    public function testNotNullValue(){

      $currencyValueHistory = new CurrencyValueHistory;

      $violationList = $this->validator->validate($currencyValueHistory);
      $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
