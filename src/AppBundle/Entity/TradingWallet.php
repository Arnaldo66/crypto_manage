<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TradingWallet
 *
 * @ORM\Table(name="trading_wallet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TradingWalletRepository")
 */
class TradingWallet
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tradingWallets")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="EuroWallet", mappedBy="tradingWallet")
     */
    private $euroWallet;

    /**
     * @ORM\OneToMany(targetEntity="EuroWallet", mappedBy="tradingWallet")
     */
    private $currencyWallets;

    /**
     * @ORM\OneToMany(targetEntity="TradingOrder", mappedBy="tradingWallet")
     */
    private $tradingOrders;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->currencyWallets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tradingOrders = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return TradingWallet
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TradingWallet
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return TradingWallet
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set euroWallet
     *
     * @param \AppBundle\Entity\EuroWallet $euroWallet
     *
     * @return TradingWallet
     */
    public function setEuroWallet(\AppBundle\Entity\EuroWallet $euroWallet = null)
    {
        $this->euroWallet = $euroWallet;

        return $this;
    }

    /**
     * Get euroWallet
     *
     * @return \AppBundle\Entity\EuroWallet
     */
    public function getEuroWallet()
    {
        return $this->euroWallet;
    }


    /**
     * Add currencyWallet
     *
     * @param \AppBundle\Entity\EuroWallet $currencyWallet
     *
     * @return TradingWallet
     */
    public function addCurrencyWallet(\AppBundle\Entity\EuroWallet $currencyWallet)
    {
        $this->currencyWallets[] = $currencyWallet;

        return $this;
    }

    /**
     * Remove currencyWallet
     *
     * @param \AppBundle\Entity\EuroWallet $currencyWallet
     */
    public function removeCurrencyWallet(\AppBundle\Entity\EuroWallet $currencyWallet)
    {
        $this->currencyWallets->removeElement($currencyWallet);
    }

    /**
     * Get currencyWallets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCurrencyWallets()
    {
        return $this->currencyWallets;
    }


    /**
     * Add tradingOrder
     *
     * @param \AppBundle\Entity\TradingOrder $tradingOrder
     *
     * @return TradingWallet
     */
    public function addTradingOrder(\AppBundle\Entity\TradingOrder $tradingOrder)
    {
        $this->tradingOrders[] = $tradingOrder;

        return $this;
    }

    /**
     * Remove tradingOrder
     *
     * @param \AppBundle\Entity\TradingOrder $tradingOrder
     */
    public function removeTradingOrder(\AppBundle\Entity\TradingOrder $tradingOrder)
    {
        $this->tradingOrders->removeElement($tradingOrder);
    }

    /**
     * Get tradingOrders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTradingOrders()
    {
        return $this->tradingOrders;
    }
}
