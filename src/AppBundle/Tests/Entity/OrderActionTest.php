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
        $orderAction = new OrderAction;
        $orderAction->setName('Bla');

        return $orderAction;
     }

     /**
      * golden pass
      */
      public function testGoldenPass(){
         $orderAction = $this->getGoldenPass();
         $violationList = $this->validator->validate($orderAction);
         $this->assertEquals($violationList->count(), self::NO_ERROR);
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
