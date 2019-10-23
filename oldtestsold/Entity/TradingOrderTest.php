<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\TradingOrder;

class TradingOrderTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 7;
    const NB_FIELD_NOT_NUM = 3;
    const NO_ERROR = 0;

    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->validator = static::$kernel->getContainer()->get('validator');
    }

    /**
     * give complete and good entity
     */
    private function getGoldenPass()
    {
        $tradingOrder = new TradingOrder;
        $tradingOrder->setPrice('1');
        $tradingOrder->setAmount('1');
        $tradingOrder->setTotal('1');
        $tradingOrder->setTradingWallet(
            $this->em->getRepository("AppBundle:TradingWallet")->find(1)
        );
        $tradingOrder->setCurrency(
            $this->em->getRepository("AppBundle:Currency")->find(1)
        );
        $tradingOrder->setOrderAction(
            $this->em->getRepository("AppBundle:OrderAction")->find(1)
        );
        $tradingOrder->setOrderStatus(
            $this->em->getRepository("AppBundle:OrderStatus")->find(1)
        );
        $tradingOrder->setOrderMethod(
            $this->em->getRepository("AppBundle:OrderMethod")->find(1)
        );

        return $tradingOrder;
    }

    /**
     * golden pass
     */
    public function testGoldenPass()
    {
        $tradingOrder = $this->getGoldenPass();
        $violationList = $this->validator->validate($tradingOrder);
        $this->assertEquals($violationList->count(), self::NO_ERROR);
    }

    /**
     * Many field must be not null
     */
    public function testNotNullValue()
    {
        $tradingOrder = new TradingOrder;

        $violationList = $this->validator->validate($tradingOrder);
        $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }

    /**
     * The
     */
    public function testWrongNumberValue()
    {
        $tradingOrder = $this->getGoldenPass();
        $tradingOrder->setPrice('lal');
        $tradingOrder->setAmount('lal');
        $tradingOrder->setTotal('yoy');

        $violationList = $this->validator->validate($tradingOrder);
        $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NUM);
    }
}
