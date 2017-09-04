<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CurrencyValueHistory
 *
 * @ORM\Table(name="currency_value_history")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyValueHistoryRepository")
 * @UniqueEntity("day")
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
     * @Assert\NotBlank()
     * @ORM\Column(name="hight_usd", type="float")
     */
    private $hightUsd;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="low_usd", type="float")
     */
    private $lowUsd;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="hight_eur", type="float")
     */
    private $hightEur;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="low_eur", type="float")
     */
    private $lowEur;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="average", type="float")
     */
    private $average;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="day", type="date", unique=true)
     */
    private $day;

    /**
     * @Assert\NotNull()
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
     * Set hightUsd
     *
     * @param float $hightUsd
     *
     * @return CurrencyValueHistory
     */
    public function setHightUsd($hightUsd)
    {
        $this->hightUsd = $hightUsd;

        return $this;
    }

    /**
     * Get hightUsd
     *
     * @return float
     */
    public function getHightUsd()
    {
        return $this->hightUsd;
    }

    /**
     * Set low
     *
     * @param float $lowUsd
     *
     * @return CurrencyValueHistory
     */
    public function setLowUsd($lowUsd)
    {
        $this->lowUsd = $lowUsd;

        return $this;
    }

    /**
     * Get lowUsd
     *
     * @return float
     */
    public function getLowUsd()
    {
        return $this->lowUsd;
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

    /**
     * Set average
     *
     * @param float $average
     *
     * @return CurrencyValueHistory
     */
    public function setAverage($average)
    {
        $this->average = $average;

        return $this;
    }

    /**
     * Get average
     *
     * @return float
     */
    public function getAverage()
    {
        return $this->average;
    }

    /**
     * Set hightEur
     *
     * @param float $hightEur
     *
     * @return CurrencyValueHistory
     */
    public function setHightEur($hightEur)
    {
        $this->hightEur = $hightEur;

        return $this;
    }

    /**
     * Get hightEur
     *
     * @return float
     */
    public function getHightEur()
    {
        return $this->hightEur;
    }

    /**
     * Set lowEur
     *
     * @param float $lowEur
     *
     * @return CurrencyValueHistory
     */
    public function setLowEur($lowEur)
    {
        $this->lowEur = $lowEur;

        return $this;
    }

    /**
     * Get lowEur
     *
     * @return float
     */
    public function getLowEur()
    {
        return $this->lowEur;
    }
}
