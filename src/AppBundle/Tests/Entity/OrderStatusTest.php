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
        $orderStatus = new OrderStatus;
        $orderStatus->setName('Bla');

        return $orderStatus;
     }

     /**
      * golden pass
      */
      public function testGoldenPass(){
         $orderStatus = $this->getGoldenPass();
         $violationList = $this->validator->validate($orderStatus);
         $this->assertEquals($violationList->count(), self::NO_ERROR);
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
