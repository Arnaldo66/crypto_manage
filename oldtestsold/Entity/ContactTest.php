<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ValidatorFactory;
use AppBundle\Entity\Contact;

class ContactTest extends KernelTestCase
{
    private $em;
    private $validator;
    const NB_FIELD_NOT_NULL = 3;
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
        $contact = new Contact;
        $contact->setEmail('test@yahoo.fr');
        $contact->setName('test');
        $contact->setMessage('testtest');

        return $contact;
    }

    /**
     * golden pass
     */
    public function testGoldenPass()
    {
        $contact = $this->getGoldenPass();
        $violationList = $this->validator->validate($contact);
        $this->assertEquals($violationList->count(), self::NO_ERROR);
    }

    /**
     * The name field must be not null
     */
    public function testNotNullValue()
    {
        $contact = new Contact;

        $violationList = $this->validator->validate($contact);
        $this->assertEquals($violationList->count(), self::NB_FIELD_NOT_NULL);
    }
}
