<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\TradingWallet;

class TradingWalletTest extends KernelTestCase
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
        $tradingWallet = new TradingWallet;
        $tradingWallet->setName('test');
        $tradingWallet->setPublic(1);
        $tradingWallet->setUser(
          $this->em->getRepository("AppBundle:User")->find(1)
        );

        return $tradingWallet;
     }

     /**
      * golden pass
      */
      public function testGoldenPass(){
         $tradingWallet = $this->getGoldenPass();
         $violationList = $this->validator->validate($tradingWallet);
         $this->assertEquals($violationList->count(), self::NO_ERROR);
      }

    /**
     * The name field must be not null
     */
    public function testNotNullValue(){

      $tradingWallet = new TradingWallet;

      $violationList = $this->validator->validate($tradingWallet);
      $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
