<?php

namespace AppBundle\Tests\BeforeAll;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FixtureImportTest extends WebTestCase
{
    public function setUp()
    {
        exec('php bin/console doctrine:fixtures:load -e test --no-interaction --purge-with-truncate');
    }

    public function testImportFixture()
    {
        $this->assertEquals(1, 1);
    }
}
