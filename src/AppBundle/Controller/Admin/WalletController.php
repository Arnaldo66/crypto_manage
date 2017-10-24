<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class WalletController extends Controller
{
    /**
     * @Route("/egbo/wallets", name="admin_wallets")
     */
    public function walletsAction()
    {
        $wallets = $this->getDoctrine()->getManager()
                  ->getRepository('AppBundle:TradingWallet')
                  ->findAll();

        return $this->render(':Admin\Wallet:wallets.html.twig', array(
            'wallets' => $wallets
        ));
    }

}
