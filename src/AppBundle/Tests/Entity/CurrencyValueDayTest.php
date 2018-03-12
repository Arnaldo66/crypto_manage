<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\CurrencyValueDay;

class CurrencyValueDayTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 1;
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
        $currencyValueDay = new CurrencyValueDay;
        $currencyValueDay->setCurrency(
            $this->em->getRepository("AppBundle:Currency")->find(1)
        );
        return $currencyValueDay;
    }

    /**
     * golden pass
     */
    public function testGoldenPass()
    {
        $currencyValueDay = $this->getGoldenPass();
        $violationList = $this->validator->validate($currencyValueDay);
        $this->assertEquals($violationList->count(), self::NO_ERROR);
    }
    /**
     * The name field must be not null
     */
    public function testNotNullValue()
    {
        $currencyValueDay = new CurrencyValueDay;

        $violationList = $this->validator->validate($currencyValueDay);
        $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
