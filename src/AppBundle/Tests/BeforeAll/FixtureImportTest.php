<?php

namespace AppBundle\Tests\BeforeAll;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FixtureImportTest extends WebTestCase
{
    public function setUp()
    {
        exec('php bin/console doctrine:database:drop -e test --force');
        exec('php bin/console doctrine:database:create -e test');
        exec('php bin/console doctrine:schema:update -e test --force');
        exec('php bin/console doctrine:fixtures:load -e test --no-interaction --purge-with-truncate');
    }

    public function testImportFixture()
    {
        $this->assertEquals(1, 1);
    }
}
