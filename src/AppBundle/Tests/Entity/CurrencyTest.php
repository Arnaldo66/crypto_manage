<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\Currency;

class CurrencyTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_ERROR_FIELD = 1;
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
        $currency = new Currency;
        $currency->setName('thisnameneverexists');
        $currency->setSymbol('bitbitbit');
        $currency->setUniqueName('test');
        $currency->setPriceUsd('4000');
        $currency->setPriceEur('3500');
        $currency->setRank(1);

        return $currency;
     }

    /**
     * give complete and good entity
     */
    public function testValidCurrency(){
      $currency = $this->getGoldenPass();

      $violationList = $this->validator->validate($currency);
      $this->assertEquals($violationList->count(), self::NO_ERROR);
    }


    /**
     * The name must be unique, "bitoin exists already"
     */
    public function testNameUnique(){
      $currency = $this->getGoldenPass();
      $currency->setUniqueName('bitcoin');

      $violationList = $this->validator->validate($currency);
      $this->assertEquals($violationList->count(), self::NB_ERROR_FIELD);
    }

    /**
     * The name less or equal than 255
     */
    public function testNameLength(){
      $currency = $this->getGoldenPass();
      $currency->setName(str_repeat("a", 256));

      $violationList = $this->validator->validate($currency);
      $this->assertEquals($violationList->count(), self::NB_ERROR_FIELD);
    }

    /**
     * The bit less or equal than 30
     */
    public function testSymbolLength(){
      $currency = $this->getGoldenPass();
      $currency->setSymbol(str_repeat("a", 31));

      $violationList = $this->validator->validate($currency);
      $this->assertEquals($violationList->count(), self::NB_ERROR_FIELD);
    }

    /**
     * The name, and dates not to be null
     */
    public function testNullValue(){
      $currency = $this->getGoldenPass();
      $currency->setName(NULL);

      $violationList = $this->validator->validate($currency);
      $this->assertEquals($violationList->count(), self::NB_ERROR_FIELD);
    }
}
