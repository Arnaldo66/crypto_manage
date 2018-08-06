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

class CreateCurrencyValueMomentCommand extends ContainerAwareCommand
{
    // add validation verification before flush entity
    private $test = 0;
    protected function configure()
    {
        $this
        ->setName('cron:create-currency-value-moment')
        ->setDescription('Fill database with details of best currency in the market at the moment')
        ->setHelp('This command fill table currency_value_moment. Launch by cron 1 time by 5 minutes.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new \GuzzleHttp\Client();
        $start = 1;
        $size = 100;
        $end = 1;
        $output_message = 'KO';
        $em = $this->getContainer()->get('doctrine')->getManager();
        //insert old value in table currencyValueDay
        $this->createCurrencyValueDay($em);
        //destroy all value
        $this->truncateTable($em);

        for($start = 1; $start <= $end; $start+=$size){
            $res = $client->request('GET', 'https://api.coinmarketcap.com/v2/ticker/?convert=EUR&limit='.$size.'&start='.$start);
            if($end === 1){
                $end = json_decode($res->getBody())->metadata->num_cryptocurrencies;
            }
            //test if the request is valid and with good content
            if ($res->getStatusCode() == '200' && $res->getHeaderLine('content-type') == 'application/json') {
                $body = json_decode($res->getBody());
                foreach ($body->data as $key => $value) {
                    $this->test++;
                    $this->createCurrencyValueMoment($em, $value);
                }
                $em->flush();
                $output_message = 'OK';
            }

            $res = $client->request('GET', 'https://api.coinmarketcap.com/v2/ticker/?convert=BTC&limit='.$size.'&start='.$start);
            if ($res->getStatusCode() == '200' && $res->getHeaderLine('content-type') == 'application/json') {
                $body = json_decode($res->getBody());
                foreach ($body->data as $key => $value) {
                    $this->updateBtcPrice($em, $value);
                }
                $em->flush();
                $output_message = 'OK BTC';
            }
        }





        $alerts = $em->getRepository('AppBundle:Alert')->findAll();
        foreach ($alerts as $alert) {
            $priceEuro = $alert->getCurrency()->getPriceEur();
            if (($priceEuro <= $alert->getPrice() && $alert->getBuy()) || ($priceEuro >= $alert->getPrice() && !$alert->getBuy())) {
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

    //update btc price
    private function updateBtcPrice($em, $value)
    {
        $currency = $em->getRepository('AppBundle:Currency')->findOneByUniqueName($value->website_slug);
        if ($currency !== null) {
            $value = get_object_vars($value);
            $btc = get_object_vars($value['quotes']->BTC);

            $currency->setPriceBtc($btc['price']);
            $moment = $em->getRepository('AppBundle:CurrencyValueMoment')->findOneByCurrency($currency);
            if ($moment !== null) {
                $moment->setPriceBtc($btc['price']);
            }
        }
    }

    //Create new Currency if not exist. Base on currency name
    private function createCurrencyValueMoment($em, $value)
    {
        $currency = $em->getRepository('AppBundle:Currency')->findOneByUniqueName($value->website_slug);
        $value = get_object_vars($value);
        $usd = get_object_vars($value['quotes']->USD);
        $euro = get_object_vars($value['quotes']->EUR);

        //create currency if not exists
        if ($currency === null) {
            $currency = new Currency;
            $currency->setName($value['name']);
            $currency->setUniqueName($value['website_slug']);
            $currency->setSymbol($value['symbol']);
            $currency->setPriceUsd($usd['price']);
            $currency->setPriceEur($euro['price']);
            $currency->setRank($value['rank']);
            $em->persist($currency);
        } else {
            $currency->setPriceUsd($usd['price']);
            $currency->setPriceEur($euro['price']);
            $currency->setMaxSupply($value['max_supply']);
            $currency->setCirculatingSupply($value['total_supply']);
        }

        $currencyValueMoment = new CurrencyValueMoment;
        $currencyValueMoment->setCurrency($currency);
        $currencyValueMoment->setRank($value['rank']);
        $currencyValueMoment->setPriceUsd($usd['price']);
        $currencyValueMoment->setMarketCapUsd($usd['market_cap']);
        $currencyValueMoment->setAvailableSupply($value['total_supply']);
        $currencyValueMoment->setTotalSupply($value['total_supply']);
        $currencyValueMoment->setPercentChange1h($usd['percent_change_1h']);
        $currencyValueMoment->setPercentChange24h($usd['percent_change_24h']);
        $currencyValueMoment->setPercentChange7d($usd['percent_change_7d']);
        $currencyValueMoment->setVolumeUsd24h($usd['volume_24h']);
        $currencyValueMoment->setLastUpdated($value['last_updated']);

        $currencyValueMoment->setPriceEur($euro['price']);
        $currencyValueMoment->setMarketCapEur($euro['market_cap']);

        $em->persist($currencyValueMoment);
    }


    //create image from distant img
    private function createImage($value)
    {
        $size = array('32','64');
        $folder = $this->getContainer()->get('kernel')->getRootDir().'/../web/images/currency-logo';
        $this->createLogoFolder($folder, $size);
        $filename = $value->id.'.png';

        if ($this->getContainer()->get('kernel')->getEnvironment() !== 'test') {
            foreach ($size as $value) {
                $new_folder = $folder .'/'. $value;
                try {
                    file_put_contents($new_folder.'/'.$filename, file_get_contents('https://files.coinmarketcap.com/static/img/coins/'.$value.'x'.$value.'/'.$filename));
                } catch (\ErrorException $e) {
                    return null;
                }
            }
        }
        return $filename;
    }

    //create folder
    private function createLogoFolder($folder, $size)
    {
        $fs = new Filesystem();

        foreach ($size as $value) {
            $new_folder = $folder .'/'. $value;
            try {
                if (!$fs->exists($new_folder)) {
                    $fs->mkdir($new_folder);
                }
            } catch (IOExceptionInterface $e) {
                throw new \Exception("An error occurred while creating your directory at ".$e->getPath());
            }
        }
    }
}
