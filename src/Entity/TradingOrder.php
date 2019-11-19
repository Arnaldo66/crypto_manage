<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TradingOrder
 *
 * @ORM\Table(name="trading_order")
 * @ORM\Entity(repositoryClass="App\Repository\TradingOrderRepository")
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
     * @Assert\Regex(pattern="/\d/")
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/\d/")
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;


    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/\d/")
     * @ORM\Column(name="total", type="float")
     */
    private $total;

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
     * @ORM\ManyToOne(targetEntity="OrderAction")
     * @ORM\JoinColumn(name="order_action_id", referencedColumnName="id", nullable=false)
     */
    private $orderAction;

    /**
     * @ORM\ManyToOne(targetEntity="OrderStatus")
     * @ORM\JoinColumn(name="order_status_id", referencedColumnName="id", nullable=false)
     */
    private $orderStatus;

    /**
     * @ORM\ManyToOne(targetEntity="OrderMethod")
     * @ORM\JoinColumn(name="order_method_id", referencedColumnName="id", nullable=false)
     */
    private $orderMethod;

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
     * @param \App\Entity\TradingWallet $tradingWallet
     *
     * @return TradingOrder
     */
    public function setTradingWallet(\App\Entity\TradingWallet $tradingWallet)
    {
        $this->tradingWallet = $tradingWallet;

        return $this;
    }

    /**
     * Get tradingWallet
     *
     * @return \App\Entity\TradingWallet
     */
    public function getTradingWallet()
    {
        return $this->tradingWallet;
    }

    /**
     * Set currency
     *
     * @param \App\Entity\Currency $currency
     *
     * @return TradingOrder
     */
    public function setCurrency(\App\Entity\Currency $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \App\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return TradingOrder
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return TradingOrder
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set orderAction
     *
     * @param \App\Entity\OrderAction $orderAction
     *
     * @return TradingOrder
     */
    public function setOrderAction(\App\Entity\OrderAction $orderAction)
    {
        $this->orderAction = $orderAction;

        return $this;
    }

    /**
     * Get orderAction
     *
     * @return \App\Entity\OrderAction
     */
    public function getOrderAction()
    {
        return $this->orderAction;
    }

    /**
     * Set orderStatus
     *
     * @param \App\Entity\OrderStatus $orderStatus
     *
     * @return TradingOrder
     */
    public function setOrderStatus(\App\Entity\OrderStatus $orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * Get orderStatus
     *
     * @return \App\Entity\OrderStatus
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Set orderMethod
     *
     * @param \App\Entity\OrderMethod $orderMethod
     *
     * @return TradingOrder
     */
    public function setOrderMethod(\App\Entity\OrderMethod $orderMethod)
    {
        $this->orderMethod = $orderMethod;

        return $this;
    }

    /**
     * Get orderMethod
     *
     * @return \App\Entity\OrderMethod
     */
    public function getOrderMethod()
    {
        return $this->orderMethod;
    }
}
