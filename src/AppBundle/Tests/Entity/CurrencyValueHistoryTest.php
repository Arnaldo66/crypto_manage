<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\CurrencyValueHistory;

class CurrencyValueHistoryTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 7;
    const NB_FIELD_UNIQUE = 1;

    public function setUp(){
      exec('php bin/console doctrine:fixtures:load -e test --no-interaction --purge-with-truncate');

      self::bootKernel();
      $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
      $this->validator = static::$kernel->getContainer()->get('validator');
    }

    /**
     * The name field must be not null
     */
    public function testNotNullValue(){

      $currencyValueHistory = new CurrencyValueHistory;

      $violationList = $this->validator->validate($currencyValueHistory);
      $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }

    /**
     * The test unique day value
     */
    public function testUniqueDayValue(){
      $currency = $this->em->getRepository('AppBundle:Currency')->find(1);

      $currencyValueHistory = new CurrencyValueHistory;
      $currencyValueHistory->setLowUsd(1.00);
      $currencyValueHistory->setLowEur(1.00);
      $currencyValueHistory->setHightUsd(1.00);
      $currencyValueHistory->setHightEur(1.00);
      $currencyValueHistory->setAverage(1.00);
      $currencyValueHistory->setCurrency($currency);
      $currencyValueHistory->setDay(new \DateTime());


      $violationList = $this->validator->validate($currencyValueHistory);
      $this->assertEquals($violationList->count(), self::NB_FIELD_UNIQUE);
    }
}
