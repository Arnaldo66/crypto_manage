<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Form\TradingWalletType;
use AppBundle\Entity\TradingWallet;
use AppBundle\Entity\EuroWallet;

class TradeController extends Controller
{


  /**
   * @Route("/user/trade/wallets", name="trade_index")
   */
   public function indexAction(){
     $em = $this->getDoctrine()->getManager();
     $wallets = $this->getUser()->getTradingWallets();

     return $this->render(':Trade:index.html.twig', array(
         'wallets' => $wallets
     ));
   }

   /**
    * @Route("/user/trade/wallets/{id}", name="trade_show")
    * @ParamConverter("tradingWallet", class="AppBundle:TradingWallet")
    */
    public function showAction(TradingWallet $tradingWallet){
      return $this->render(':Trade:show.html.twig', array(
          'tradingWallet' => $tradingWallet
      ));
    }

    /**
     * @Route("/user/trade/new", name="trade_new")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tradingWallet = new TradingWallet();
        $tradingWallet->setPublic(true);
        $tradingWallet->setUser($this->getUser());
        $tradingWallet->setEuroWallet($this->createEuroWallet($em,$tradingWallet));

        $form = $this->createForm(TradingWalletType::class, $tradingWallet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $em->persist($tradingWallet);
          $em->flush();

          $this->addFlash('success-message','Votre portefeuille a bien été crée !');
          return $this->redirectToRoute('trade_index');
        }

        return $this->render(':Trade:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Create a new euro wallet by default
     */
     private function createEuroWallet($em,$tradingWallet){
       $euroWallet = new EuroWallet;
       $euroWallet->setAmount(15000.00);
       $euroWallet->setTradingWallet($tradingWallet);
       $em->persist($euroWallet);
       return $euroWallet;
     }

}
