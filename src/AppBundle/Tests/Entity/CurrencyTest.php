<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\Currency;

class CurrencyTest extends KernelTestCase
{
    private $em;
    private $validator;

    public function setUp(){
      self::bootKernel();
      $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
      $this->validator = static::$kernel->getContainer()->get('validator');
    }

    /**
     * The name must be unique, "bitoin exists already"
     */
    public function testNameUnique(){

      $currency = new Currency;
      $currency->setName('Bitcoin');
      $currency->setSymbol('BIT');
      $currency->setUniqueName('bitcoin');
      $currency->setPriceUsd('4000');
      $currency->setPriceEur('3500');
      $currency->setRank(1);
      $currency->setCreatedAt(new \DateTime());
      $currency->setUpdatedAt(new \DateTime());

      $violationList = $this->validator->validate($currency);
      $this->assertEquals($violationList->count(), 1);
    }

    /**
     * The name less or equal than 255
     */
    public function testNameLength(){

      $currency = new Currency;
      $currency->setName(str_repeat("a", 256));
      $currency->setSymbol('BIT');
      $currency->setUniqueName('test');
      $currency->setPriceUsd('4000');
      $currency->setPriceEur('3500');
      $currency->setRank(1);
      $currency->setCreatedAt(new \DateTime());
      $currency->setUpdatedAt(new \DateTime());

      $violationList = $this->validator->validate($currency);
      $this->assertEquals($violationList->count(), 1);
    }

    /**
     * The bit less or equal than 30
     */
    public function testSymbolLength(){

      $currency = new Currency;
      $currency->setName('thisnameneverexists');
      $currency->setSymbol(str_repeat("a", 31));
      $currency->setUniqueName('test');
      $currency->setPriceUsd('4000');
      $currency->setPriceEur('3500');
      $currency->setRank(1);
      $currency->setCreatedAt(new \DateTime());
      $currency->setUpdatedAt(new \DateTime());

      $violationList = $this->validator->validate($currency);
      $this->assertEquals($violationList->count(), 1);
    }

    /**
     * The name, and dates not to be null
     */
    public function testNullValue(){

      $currency = new Currency;
      $currency->setName(NULL);
      $currency->setSymbol('BIT');
      $currency->setUniqueName('test');
      $currency->setPriceUsd('4000');
      $currency->setPriceEur('3500');
      $currency->setRank(1);

      $violationList = $this->validator->validate($currency);
      $this->assertEquals($violationList->count(), 1);
    }

    /**
     * The name must be unique, "bitoin exists already"
     */
    public function testValidCurrency(){

      $currency = new Currency;
      $currency->setName('thisnameneverexists');
      $currency->setSymbol('bitbitbit');
      $currency->setUniqueName('test');
      $currency->setPriceUsd('4000');
      $currency->setPriceEur('3500');
      $currency->setRank(1);

      $violationList = $this->validator->validate($currency);
      $this->assertEquals($violationList->count(), 0);
    }
}
