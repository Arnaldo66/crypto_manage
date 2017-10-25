<?php

namespace AppBundle\Repository;

/**
 * CurrencyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CurrencyRepository extends \Doctrine\ORM\EntityRepository
{
  /**
   * get data for graph by last 12 month
   */
   public function getDataLastMonth($currency){
      $conn = $this->_em->getConnection();
      $conn->executeUpdate("SET sql_mode = '';");
      $query = ("
              SELECT  TRUNCATE(AVG(average_eur),2) as ".$currency->getUniqueName().",
                      case when length(MONTH(day))=1
                        then CONCAT(YEAR(day), '-0', MONTH(day), '-01')
                        else CONCAT(YEAR(day), '-', MONTH(day), '-01')
                      end as period
              FROM currency_value_history
              WHERE day >= concat(YEAR(CURRENT_DATE)-1,'-', MONTH(CURRENT_DATE) ,'-01')
              AND currency_id = ".$currency->getId()."
              GROUP BY YEAR(day), MONTH(day)
              ORDER BY day ASC
       ");
       return $conn->query($query)->fetchAll();
   }
}
