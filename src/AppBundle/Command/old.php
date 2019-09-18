<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use AppBundle\Entity\Currency;
use AppBundle\Entity\CurrencyValueMoment;
use AppBundle\Entity\CurrencyValueDay;

class old extends ContainerAwareCommand
{
    const API_ENDPOINT='https://api.coingecko.com/api/v3/coins';
    private $client;



    // add validation verification before flush entity
    protected function configure()
    {
        $this
        ->setName('cron:old')
        ->setDescription('Fill database with details of best currency in the market at the moment')
        ->setHelp('This command fill table currency_value_moment. Launch by cron 1 time by 5 minutes.');

        $this->client = new \GuzzleHttp\Client();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output_message = 'KO';
        $em = $this->getContainer()->get('doctrine')->getManager();
        //insert old value in table currencyValueDay
        $this->createCurrencyValueDay($em);
        //destroy all value
        $this->truncateTable($em);
        $this->resetRank($em);

        $arrayMonney = ['USD', 'EUR', 'BTC'];
        foreach ($arrayMonney as $monney) {
            $res = $this->client->request('GET', self::API_ENDPOINT . $monney);
            if ($res->getStatusCode() == '200') {
                $body = json_decode($res->getBody());
                foreach ($body->data as $key => $value) {
                    $this->createCurrencyValueMoment($em, $value, $monney);
                }
                $em->flush();
                $output_message = 'OK';
            }
        }

        $alerts = $em->getRepository('AppBundle:Alert')->findAll();
        foreach ($alerts as $alert) {
            $priceEuro = $alert->getCurrency()->getPriceEur();
            if (($priceEuro <= $alert->getPrice() && $alert->getBuy()) ||
                ($priceEuro >= $alert->getPrice() && !$alert->getBuy())) {
                    $alert->setCanDelete(1);
                    $this->sendAlertEmail($alert);
                    $em->remove($alert);
            }
        }
        //remove all can delete alert
        $em->flush();
        $output->writeln($output_message);
    }

    /**
     * send email to user for alert
     */
    private function sendAlertEmail($alert)
    {
        $mailer = $this->getContainer()->get('mailer');
        $type = "Achat";
        if ($alert->getBuy() == 0) {
            $type = "Vente";
        }
        $message = (new \Swift_Message('Une alerte a été soulevé'))
           ->setFrom('admin@e-goldenboy.com')
           ->setTo($alert->getUser()->getEmail())
           ->setBody(
               'Message: Une alerte a été soulevé<br/>
               Type: '.$type.'<br/>
               Crypto-monnaie: '.$alert->getCurrency()->getName().'<br/>
               Seuil souhaité: '.$alert->getPrice().'<br/>
               Valeur actuelle: '.$alert->getCurrency()->getPriceEur(),
               'text/html'
           );
        $mailer->send($message);
    }

    //insert into CurrencyValueDay
    private function createCurrencyValueDay($em)
    {
        $momentValues = $em->getRepository('AppBundle:CurrencyValueMoment')->findAll();
        foreach ($momentValues as $momentValue) {
            $currencyValueDay = new CurrencyValueDay;
            $currencyValueDay->setCurrency($momentValue->getCurrency());
            $currencyValueDay->setPriceUsd($momentValue->getPriceUsd());
            $currencyValueDay->setPriceBtc($momentValue->getPriceBtc());
            $currencyValueDay->setPriceEur($momentValue->getPriceEur());
            $currencyValueDay->setVolumeUsd24h($momentValue->getVolumeUsd24h());

            $em->persist($currencyValueDay);
        }

        $em->flush();
    }


    //truncate table currency_moment_value
    private function truncateTable($em)
    {
        $connection = $em->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('currency_value_moment', true));
    }

    private function resetRank($em)
    {
        $connection = $em->getConnection();
        $query = (' update currency set rank = 1001
                    where not exists(
                        select * from currency_value_moment where currency.id = currency_value_moment.currency_id)
        ');
        $connection->executeUpdate($query);
    }

    //Create new Currency if not exist. Base on currency name
    private function createCurrencyValueMoment($em, $value, $monney)
    {
        $currency = $em->getRepository('AppBundle:Currency')->findOneByUniqueName($value->slug);
        $value = get_object_vars($value);

        if ($monney == 'USD') {
            $dataCurrency = get_object_vars($value['quote']->USD);
        } elseif ($monney == 'EUR') {
            $dataCurrency = get_object_vars($value['quote']->EUR);
        } else {
            $dataCurrency = get_object_vars($value['quote']->BTC);
        }

        $currencyValueMoment = null;
        //create currency if not exists
        if ($currency === null) {
            $currency = new Currency;
            $currency->setName($value['name']);
            $currency->setUniqueName($value['slug']);
            $currency->setSymbol($value['symbol']);
            $currency->setPriceUsd($dataCurrency['price']);
            $currency->setRank($value['cmc_rank']);
            $em->persist($currency);
        } else {
            $currencyValueMoment = $em->getRepository('AppBundle:CurrencyValueMoment')
                ->findOneByCurrency($currency);
            if ($monney == 'USD') {
                $currency->setPriceUsd($dataCurrency['price']);
            } elseif ($monney == 'EUR') {
                $currency->setPriceEur($dataCurrency['price']);
            } else {
                $currency->setPriceBtc($dataCurrency['price']);
            }
            $currency->setRank($value['cmc_rank']);
            $currency->setMaxSupply($value['max_supply']);
            $currency->setCirculatingSupply($value['total_supply']);
        }

        if ($currencyValueMoment === null) {
            $currencyValueMoment = new CurrencyValueMoment;
            $currencyValueMoment->setCurrency($currency);
            $currencyValueMoment->setAvailableSupply($value['total_supply']);
            $currencyValueMoment->setTotalSupply($value['total_supply']);
            $currencyValueMoment->setLastUpdated(time());
        }

        if ($monney == 'USD') {
            $currencyValueMoment->setMarketCapUsd($dataCurrency['market_cap']);
            $currencyValueMoment->setPercentChange1h($dataCurrency['percent_change_1h']);
            $currencyValueMoment->setPercentChange24h($dataCurrency['percent_change_24h']);
            $currencyValueMoment->setPercentChange7d($dataCurrency['percent_change_7d']);
            $currencyValueMoment->setVolumeUsd24h($dataCurrency['volume_24h']);
            $currencyValueMoment->setPriceUsd($dataCurrency['price']);
        } elseif ($monney == 'EUR') {
            $currencyValueMoment->setPriceEur($dataCurrency['price']);
            $currencyValueMoment->setMarketCapEur($dataCurrency['market_cap']);
        } else {
            $currencyValueMoment->setPriceBtc($dataCurrency['price']);
        }
        $currencyValueMoment->setRank($value['cmc_rank']);


        $em->persist($currencyValueMoment);
    }
}
