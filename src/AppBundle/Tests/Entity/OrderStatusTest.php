<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\OrderStatus;

class OrderStatusTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 1;

    public function setUp(){
      self::bootKernel();
      $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
      $this->validator = static::$kernel->getContainer()->get('validator');
    }

    /**
     * The name field must be not null
     */
    public function testNotNullValue(){

      $orderStatus = new OrderStatus;

      $violationList = $this->validator->validate($orderStatus);
      $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
