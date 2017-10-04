<?php
namespace AppBundle\Test\Command;

use AppBundle\Command\CronCheckLimitActionCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CronCheckLimitCommandTest extends KernelTestCase
{
  public function testExecute()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $application->add(new CronCheckLimitActionCommand());

        $command = $application->find('cron:check-limit-action');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName()
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('OK', $output);
    }
}
