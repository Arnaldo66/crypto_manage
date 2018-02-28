<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MyCryptoWalletController extends Controller
{
    /**
     * @Route("/u/my-crypto/dashboard", name="my_crypto_dashboard")
     */
    public function indexAction()
    {
        return $this->render(':MyCryptoWallet:dashboard.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/u/my-crypto/new", name="my_crypto_new")
     */
    public function newAction()
    {
        return $this->render(':MyCryptoWallet:new.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/u/my-crypto/{id}", name="my_crypto_show")
     */
    public function showAction()
    {
        return $this->render(':MyCryptoWallet:show.html.twig', array(
            // ...
        ));
    }

}
