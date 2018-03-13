<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\MyCryptoWalletDetail;

class MyCryptoWalletDetailTest extends KernelTestCase
{
    private $eManager;
    private $validator;
    const NB_FIELD_NOT_NULL = 3;
    const NO_ERROR = 0;

    public function setUp()
    {
        self::bootKernel();
        $this->eManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->validator = static::$kernel->getContainer()->get('validator');
    }


    /**
     * give complete and good entity
     */
    private function getGoldenPass()
    {
        $myCryptoWalletDetail = new MyCryptoWalletDetail;
        $myCryptoWalletDetail->setAmount('1.145');
        $myCryptoWalletDetail->setMyCryptoWallet(
            $this->eManager->getRepository("AppBundle:MyCryptoWallet")->find(1)
        );
        $myCryptoWalletDetail->setCurrency(
            $this->eManager->getRepository("AppBundle:Currency")->find(1)
        );

        return $myCryptoWalletDetail;
    }

    /**
     * golden pass
     */
    public function testGoldenPass()
    {
        $myCryptoWalletDetail = $this->getGoldenPass();
        $violationList = $this->validator->validate($myCryptoWalletDetail);
        $this->assertEquals($violationList->count(), self::NO_ERROR);
    }

    /**
     * The name field must be not null
     */
    public function testNotNullValue()
    {
        $myCryptoWalletDetail = new MyCryptoWalletDetail;
        $violationList = $this->validator->validate($myCryptoWalletDetail);
        $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
