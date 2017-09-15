<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\TradingOrder;

class TradingOrderTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 5;

    public function setUp(){
      self::bootKernel();
      $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
      $this->validator = static::$kernel->getContainer()->get('validator');
    }

    /**
     * The name field must be not null
     */
    public function testNotNullValue(){

      $tradingOrder = new TradingOrder;

      $violationList = $this->validator->validate($tradingOrder);
      $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
