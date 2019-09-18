<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use AppBundle\Entity\Currency;
use AppBundle\Entity\CurrencyValueMoment;
use AppBundle\Entity\UpdatePriceLogs;

class CreateCurrencyValueMomentCommand extends ContainerAwareCommand
{
    const API_ENDPOINT = 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&per_pages=250';
    const LIMIT_CALL_BY_MINUTE = 100;
    //http://www.convertisseur-euros.com/api.php?d1=EUR&d2=USD&x=100&t=json taux change euro dollars
    private $client;



    // add validation verification before flush entity
    protected function configure()
    {
        $this
        ->setName('cron:create-currency-value-moment')
        ->setDescription('Fill database with details of best currency in the market at the moment')
        ->setHelp('This command fill table currency_value_moment. Launch by cron 1 time by 5 minutes.');

        $this->client = new \GuzzleHttp\Client();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        //log one import OK by day
        $this->beginLog($entityManager);
        $this->truncateTable($entityManager);
        $this->doProcessUpdatePrice($entityManager);

        ////call api and insert into table
        ////$sendAlertEmail
        //// end log
    }

    private function doProcessUpdatePrice($entityManager)
    {
        $allCoinsRequest = $this->client->request('GET', self::API_ENDPOINT);
        if ($allCoinsRequest->getStatusCode() == '200') {
            $allCoins = json_decode($allCoinsRequest->getBody());
            foreach ($allCoins as $coin) {
                dump($coin);
                die();
            }
        }
    }


    private function beginLog($entityManager)
    {
        $dateDay = new \DateTime();
        $entity = $entityManager->getRepository(UpdatePriceLogs::class)->findOneBy(
            [
                'success' => true,
                'createdAt' => $dateDay
            ]
        );

        if ($entity === null) {
            $entity = new UpdatePriceLogs();
            $entity->setCreatedAt($dateDay);
            $entity->setSuccess(true);

            $entityManager->persist($entity);
            $entityManager->flush();
        }
    }

    private function truncateTable($entityManager)
    {
        $connection = $entityManager->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('currency_value_moment', true));
    }
}
