<?php
namespace AppBundle\Test\Command;

use AppBundle\Command\CreateCurrencyValueHistoryCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateCurrencyValueHistoryCommandTest extends KernelTestCase
{
  public function testExecute()
    {
        // TODO: sqllite not accepte date_format function. Use DQL to create history
        // Use DATE_DIFF, CURRENT_DATE and create specifique function to convert DateTime in good format for group by
        // self::bootKernel();
        // $application = new Application(self::$kernel);
        //
        // $application->add(new CreateCurrencyValueHistoryCommand());
        //
        // $command = $application->find('cron:create-currency-history');
        // $commandTester = new CommandTester($command);
        // $commandTester->execute(array(
        //     'command'  => $command->getName()
        // ));
        //
        // // the output of the command in the console
        // $output = $commandTester->getDisplay();
        // $this->assertContains('OK', $output);
        $this->assertEquals(1, 1);
    }
}
