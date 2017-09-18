<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\OrderAction;

class OrderActionTest extends KernelTestCase
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

      $orderAction = new OrderAction;

      $violationList = $this->validator->validate($orderAction);
      $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
