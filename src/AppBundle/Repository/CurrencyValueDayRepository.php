<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;

/**
 * CurrencyValueDayRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CurrencyValueDayRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * return MIN && MAX By date
     */
     public function getHistoricalValueByDay(){
       $conn = $this->_em->getConnection();
       $query = (' SELECT   MIN(currency_value_day.price_eur) as min_euro,
                            MIN(currency_value_day.price_usd) as min_usd,
                            AVG(currency_value_day.price_eur) as avg_euro,
                            MAX(currency_value_day.price_eur) as max_euro,
                            MAX(currency_value_day.price_usd) as max_usd,
                            AVG(currency_value_day.price_usd) as avg_usd,
                            DATE_FORMAT(currency_value_day.created_at,"%Y-%m-%d") as date_day,
                            currency_id
                   FROM     currency_value_day
                   WHERE    DATE_FORMAT(currency_value_day.created_at,"%Y-%m-%d") < DATE_FORMAT(NOW(),"%Y-%m-%d")
                   GROUP BY currency_id, date_day
                   HAVING   min_euro is not null
                    AND     min_usd is not null
                    AND     avg_euro is not null
                    AND     max_euro is not null
                    AND     max_usd is not null
                    AND     avg_usd is not null
                   ');
       return $conn->query($query)->fetchAll();
    }

    /**
     * truncate table after historication
     */
     public function truncateTable(){
       $connection = $this->_em->getConnection();
       $platform   = $connection->getDatabasePlatform();
       $connection->executeUpdate($platform->getTruncateTableSQL('currency_value_day', true));
     }
}
