<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MyCryptoWalletController extends Controller
{
    /**
     * @Route("/u/my-crypto/dashboard")
     */
    public function indexAction()
    {
        return $this->render(':MyCryptoWallet:dashboard.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/u/my-crypto/new")
     */
    public function newAction()
    {
        return $this->render(':MyCryptoWallet:new.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/u/my-crypto/{id}")
     */
    public function showAction()
    {
        return $this->render(':MyCryptoWallet:show.html.twig', array(
            // ...
        ));
    }

}
