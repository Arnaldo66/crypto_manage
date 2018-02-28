<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="currency_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="TradingWallet", mappedBy="user")
     */
    private $tradingWallets;

    /**
     * @ORM\OneToMany(targetEntity="Alert", mappedBy="user")
     */
    private $alerts;

    /**
     * @ORM\OneToMany(targetEntity="MyCryptoWallet", mappedBy="user")
     */
    private $myCryptoWallets;

    public function __construct()
    {
      parent::__construct();
      $this->tradingWallets = new \Doctrine\Common\Collections\ArrayCollection();
      $this->alerts = new \Doctrine\Common\Collections\ArrayCollection();
      $this->myCryptoWallets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tradingWallet
     *
     * @param \AppBundle\Entity\TradingWallet $tradingWallet
     *
     * @return User
     */
    public function addTradingWallet(\AppBundle\Entity\TradingWallet $tradingWallet)
    {
        $this->tradingWallets[] = $tradingWallet;

        return $this;
    }

    /**
     * Remove tradingWallet
     *
     * @param \AppBundle\Entity\TradingWallet $tradingWallet
     */
    public function removeTradingWallet(\AppBundle\Entity\TradingWallet $tradingWallet)
    {
        $this->tradingWallets->removeElement($tradingWallet);
    }

    /**
     * Get tradingWallets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTradingWallets()
    {
        return $this->tradingWallets;
    }

    /**
     * Add alert
     *
     * @param \AppBundle\Entity\Alert $alert
     *
     * @return User
     */
    public function addAlert(\AppBundle\Entity\Alert $alert)
    {
        $this->alerts[] = $alert;

        return $this;
    }

    /**
     * Remove alert
     *
     * @param \AppBundle\Entity\Alert $alert
     */
    public function removeAlert(\AppBundle\Entity\Alert $alert)
    {
        $this->alerts->removeElement($alert);
    }

    /**
     * Get alerts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

    /**
     * Add myCryptoWallet
     *
     * @param \AppBundle\Entity\MyCryptoWallet $myCryptoWallet
     *
     * @return User
     */
    public function addMyCryptoWallet(\AppBundle\Entity\MyCryptoWallet $myCryptoWallet)
    {
        $this->myCryptoWallets[] = $myCryptoWallet;

        return $this;
    }

    /**
     * Remove myCryptoWallet
     *
     * @param \AppBundle\Entity\MyCryptoWallet $myCryptoWallet
     */
    public function removeMyCryptoWallet(\AppBundle\Entity\MyCryptoWallet $myCryptoWallet)
    {
        $this->myCryptoWallets->removeElement($myCryptoWallet);
    }

    /**
     * Get myCryptoWallets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMyCryptoWallets()
    {
        return $this->myCryptoWallets;
    }
}
