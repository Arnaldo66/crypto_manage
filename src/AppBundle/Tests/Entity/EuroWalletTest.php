<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\EuroWallet;

class EuroWalletTest extends KernelTestCase
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
        $euroWallet = new EuroWallet;
        $euroWallet->setAmount(1);
        $euroWallet->setTradingWallet(
          $this->em->getRepository("AppBundle:TradingWallet")->find(1)
        );

        return $euroWallet;
     }

     /**
      * golden pass
      */
      public function testGoldenPass(){
         $euroWallet = $this->getGoldenPass();
         $violationList = $this->validator->validate($euroWallet);
         $this->assertEquals($violationList->count(), self::NO_ERROR);
      }

    /**
     * The name field must be not null
     */
    public function testNotNullValue(){
      $euroWallet = new EuroWallet;

      $violationList = $this->validator->validate($euroWallet);
      $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
