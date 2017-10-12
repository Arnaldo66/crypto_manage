<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\CurrencyWallet;

class CurrencyWalletTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 3;
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
        $currencyWallet = new CurrencyWallet;
        $currencyWallet->setAmount(1);
        $currencyWallet->setTradingWallet(
          $this->em->getRepository("AppBundle:TradingWallet")->find(1)
        );
        $currencyWallet->setCurrency(
          $this->em->getRepository("AppBundle:Currency")->find(1)
        );

        return $currencyWallet;
     }

     /**
      * golden pass
      */
      public function testGoldenPass(){
         $currencyWallet = $this->getGoldenPass();
         $violationList = $this->validator->validate($currencyWallet);
         $this->assertEquals($violationList->count(), self::NO_ERROR);
      }

    /**
     * The name field must be not null
     */
    public function testNotNullValue(){

      $currencyWallet = new CurrencyWallet;

      $violationList = $this->validator->validate($currencyWallet);
      $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
