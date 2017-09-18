<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TradingOrder
 *
 * @ORM\Table(name="trading_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TradingOrderRepository")
 */
class TradingOrder
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
     * @ORM\Column(name="nb_currency", type="float")
     */
    private $nbCurrency;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;


    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="TradingWallet", inversedBy="tradingOrders")
     * @ORM\JoinColumn(name="trading_wallet_id", referencedColumnName="id", nullable=false)
     */
    private $tradingWallet;

    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id", nullable=false)
     */
    private $currency;

    /**
     * @Assert\NotNull()
     * @ORM\OneToOne(targetEntity="OrderAction")
     * @ORM\JoinColumn(name="order_action_id", referencedColumnName="id", nullable=false)
     */
    private $orderAction;

    /**
     * @Assert\NotNull()
     * @ORM\OneToOne(targetEntity="OrderStatus")
     * @ORM\JoinColumn(name="order_status_id", referencedColumnName="id", nullable=false)
     */
    private $OrderStatus;

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
     * Set nbCurrency
     *
     * @param float $nbCurrency
     *
     * @return TradingOrder
     */
    public function setNbCurrency($nbCurrency)
    {
        $this->nbCurrency = $nbCurrency;

        return $this;
    }

    /**
     * Get nbCurrency
     *
     * @return float
     */
    public function getNbCurrency()
    {
        return $this->nbCurrency;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TradingOrder
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return TradingOrder
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set tradingWallet
     *
     * @param \AppBundle\Entity\TradingWallet $tradingWallet
     *
     * @return TradingOrder
     */
    public function setTradingWallet(\AppBundle\Entity\TradingWallet $tradingWallet)
    {
        $this->tradingWallet = $tradingWallet;

        return $this;
    }

    /**
     * Get tradingWallet
     *
     * @return \AppBundle\Entity\TradingWallet
     */
    public function getTradingWallet()
    {
        return $this->tradingWallet;
    }

    /**
     * Set currency
     *
     * @param \AppBundle\Entity\Currency $currency
     *
     * @return TradingOrder
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
     * Set orderAction
     *
     * @param \AppBundle\Entity\OrderAction $orderAction
     *
     * @return TradingOrder
     */
    public function setOrderAction(\AppBundle\Entity\OrderAction $orderAction)
    {
        $this->orderAction = $orderAction;

        return $this;
    }

    /**
     * Get orderAction
     *
     * @return \AppBundle\Entity\OrderAction
     */
    public function getOrderAction()
    {
        return $this->orderAction;
    }

    /**
     * Set orderStatus
     *
     * @param \AppBundle\Entity\OrderStatus $orderStatus
     *
     * @return TradingOrder
     */
    public function setOrderStatus(\AppBundle\Entity\OrderStatus $orderStatus)
    {
        $this->OrderStatus = $orderStatus;

        return $this;
    }

    /**
     * Get orderStatus
     *
     * @return \AppBundle\Entity\OrderStatus
     */
    public function getOrderStatus()
    {
        return $this->OrderStatus;
    }
}
