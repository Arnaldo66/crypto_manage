<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\TradingWallet;

class TradingWalletTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 4;
    const NB_FIELD_ONE_ERROR = 1;
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
        $tradingWallet = new TradingWallet;
        $tradingWallet->setName('test');
        $tradingWallet->setInitialAmount(100);
        $tradingWallet->setPublic(1);
        $tradingWallet->setUser(
            $this->em->getRepository("AppBundle:User")->find(1)
        );

        return $tradingWallet;
    }

    /**
     * golden pass
     */
    public function testGoldenPass()
    {
        $tradingWallet = $this->getGoldenPass();
        $violationList = $this->validator->validate($tradingWallet);
        $this->assertEquals($violationList->count(), self::NO_ERROR);
    }

    /**
     * The name field must be not null
     */
    public function testNotNullValue()
    {
        $tradingWallet = new TradingWallet;

        $violationList = $this->validator->validate($tradingWallet);
        $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }

    /**
     * The nmin value initial amount
     */
    public function testMinValue()
    {
        $tradingWallet = $this->getGoldenPass();
        $tradingWallet->setInitialAmount(0);

        $violationList = $this->validator->validate($tradingWallet);
        $this->assertEquals($violationList->count(), self::NB_FIELD_ONE_ERROR);
    }

    /**
     * The max value initial amount
     */
    public function testMaxValue()
    {
        $tradingWallet = $this->getGoldenPass();
        $tradingWallet->setInitialAmount(2000000);

        $violationList = $this->validator->validate($tradingWallet);
        $this->assertEquals($violationList->count(), self::NB_FIELD_ONE_ERROR);
    }
}
