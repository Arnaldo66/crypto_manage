<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CurrencyValueHistory
 *
 * @ORM\Table(name="currency_value_history")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyValueHistoryRepository")
 */
class CurrencyValueHistory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="open", type="float")
     */
    private $open;

    /**
     * @var float
     *
     * @ORM\Column(name="hight", type="float")
     */
    private $hight;

    /**
     * @var float
     *
     * @ORM\Column(name="low", type="float")
     */
    private $low;

    /**
     * @var float
     *
     * @ORM\Column(name="close", type="float")
     */
    private $close;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="day", type="date", unique=true)
     */
    private $day;

    /**
     * @ORM\ManyToOne(targetEntity="Currency", inversedBy="currencyHistoryValues")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id", nullable=false)
     */
    private $currency;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set open
     *
     * @param float $open
     *
     * @return CurrencyValueHistory
     */
    public function setOpen($open)
    {
        $this->open = $open;

        return $this;
    }

    /**
     * Get open
     *
     * @return float
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * Set hight
     *
     * @param float $hight
     *
     * @return CurrencyValueHistory
     */
    public function setHight($hight)
    {
        $this->hight = $hight;

        return $this;
    }

    /**
     * Get hight
     *
     * @return float
     */
    public function getHight()
    {
        return $this->hight;
    }

    /**
     * Set low
     *
     * @param float $low
     *
     * @return CurrencyValueHistory
     */
    public function setLow($low)
    {
        $this->low = $low;

        return $this;
    }

    /**
     * Get low
     *
     * @return float
     */
    public function getLow()
    {
        return $this->low;
    }

    /**
     * Set close
     *
     * @param float $close
     *
     * @return CurrencyValueHistory
     */
    public function setClose($close)
    {
        $this->close = $close;

        return $this;
    }

    /**
     * Get close
     *
     * @return float
     */
    public function getClose()
    {
        return $this->close;
    }

    /**
     * Set day
     *
     * @param \DateTime $day
     *
     * @return CurrencyValueHistory
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return \DateTime
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set currency
     *
     * @param \AppBundle\Entity\Currency $currency
     *
     * @return CurrencyValueHistory
     */
    public function setCurrency(\AppBundle\Entity\Currency $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \AppBundle\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
