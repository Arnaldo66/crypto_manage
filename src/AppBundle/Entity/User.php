<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="currency_user")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;


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
        $this->isActive = true;
        $this->tradingWallets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->alerts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myCryptoWallets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized, ['allowed_classes' => false]);
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
