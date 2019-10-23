<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\Alert;

class AlertTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 4;
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
        $alert = new Alert;
        $alert->setPrice('12.5');
        $alert->setBuy(1);
        $alert->setCurrency(
            $this->em->getRepository("AppBundle:Currency")->find(1)
        );
        $alert->setUser(
            $this->em->getRepository("AppBundle:User")->find(1)
        );

        return $alert;
    }

    /**
     * golden pass
     */
    public function testGoldenPass()
    {
        $alert = $this->getGoldenPass();
        $violationList = $this->validator->validate($alert);
        $this->assertEquals($violationList->count(), self::NO_ERROR);
    }

    /**
     * The name field must be not null
     */
    public function testNotNullValue()
    {
        $alert = new Alert;

        $violationList = $this->validator->validate($alert);
        $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
