<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\MyCryptoWallet;

class MyCryptoWalletTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 2;
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
        $myCryptoWallet = new MyCryptoWallet;
        $myCryptoWallet->setName('myname');
        $myCryptoWallet->setUser(
            $this->em->getRepository("AppBundle:User")->find(1)
        );

        return $myCryptoWallet;
    }

    /**
     * golden pass
     */
    public function testGoldenPass()
    {
        $myCryptoWallet = $this->getGoldenPass();
        $violationList = $this->validator->validate($myCryptoWallet);
        $this->assertEquals($violationList->count(), self::NO_ERROR);
    }

    /**
     * The name field must be not null
     */
    public function testNotNullValue()
    {
        $myCryptoWallet = new MyCryptoWallet;

        $violationList = $this->validator->validate($myCryptoWallet);
        $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
