<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Form\Type\TradingWalletType;
use AppBundle\Entity\TradingWallet;
use AppBundle\Entity\EuroWallet;
use AppBundle\Service\WalletManager;

class TradeController extends Controller
{
  //TODO: change trade to wallet.
  /**
   * @Route("/u/trade/wallets", name="trade_index")
   */
   public function indexAction(){
     $wallets = $this->getUser()->getTradingWallets();

     return $this->render(':Trade:index.html.twig', array(
         'wallets' => $wallets
     ));
   }

   /**
    * @Route("/u/trade/wallets/{id}", name="trade_show")
    * @ParamConverter("tradingWallet", class="AppBundle:TradingWallet")
    */
    public function showAction(TradingWallet $tradingWallet, WalletManager $walletManager){
      $session = $this->get('session');
      $session->set('current_wallet_id', $tradingWallet->getId());

      if($this->getUser()->getId() !== $tradingWallet->getUser()->getId()){
        throw new AccessDeniedException('Access denied: It\'s not your wallet');
      }
      $totalCurrencies = $walletManager->getTotalCurrencyWalletValue($tradingWallet);
      return $this->render(':Trade:show.html.twig', array(
          'tradingWallet' => $tradingWallet, 'totalCurrencies' => $totalCurrencies
      ));
    }

    /**
     * @Route("/u/trade/new", name="trade_new")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tradingWallet = new TradingWallet();
        $tradingWallet->setPublic(true);
        $tradingWallet->setUser($this->getUser());

        $form = $this->createForm(TradingWalletType::class, $tradingWallet);
        $form->handleRequest($request);
        $tradingWallet->setEuroWallet($this->createEuroWallet($em,$tradingWallet,$form["amount"]->getData()));

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
     private function createEuroWallet($em,$tradingWallet,$amount){
       $value = $this->getValueFromAmount($amount);
       $tradingWallet->setInitialAmount($value);

       $euroWallet = new EuroWallet;
       $euroWallet->setAmount($value);
       $euroWallet->setTradingWallet($tradingWallet);

       $em->persist($euroWallet);
       return $euroWallet;
     }

     /**
      * Get value from choice amount
      */
     private function getValueFromAmount($amount){
       if($amount == 1){
         return 100.00;
       }elseif($amount == 2){
         return 1000.00;
       }elseif ($amount == 3) {
         return 5000.00;
       }elseif ($amount == 4) {
         return 10000.00;
       }else {
         return 15000.00;
       }
     }

     /**
      * get ajax to remove wallet
      * @Route("/u/trade/delete/{id}", name="trade_delete", options = { "expose" = true })
      * @Method({"DELETE", "POST"})
      * @ParamConverter("tradingWallet", class="AppBundle:TradingWallet")
      */
      public function deleteAction(TradingWallet $tradingWallet){
        if($this->getUser()->getId() !== $tradingWallet->getUser()->getId()){
          throw new AccessDeniedException('Access denied: It\'s not your wallet');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($tradingWallet);
        $em->flush();

        $this->addFlash('success-message','Votre portefeuille a bien été supprimé !');
        return $this->redirectToRoute('trade_index');
      }
}
