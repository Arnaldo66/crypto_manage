<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Form\Type\TradingWalletType;
use AppBundle\Entity\TradingWallet;
use AppBundle\Entity\EuroWallet;
use AppBundle\Service\WalletManager;

class TradeController extends Controller
{
    /**
     * @Route("/u/trade/wallets", name="trade_index")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $wallets = $this->getUser()->getTradingWallets();

        return $this->render(':Trade:index.html.twig', array(
            'wallets' => $wallets
        ));
    }

    /**
     * @Route("/portefeuilles-publics", name="trade_public")
     * @Method({"GET"})
     */
    public function publicWalletAction()
    {
        $em = $this->getDoctrine()->getManager();
        $wallets = $em->getRepository('AppBundle:TradingWallet')->findBy(array('public'=>1));

        return $this->render(':Trade:public_wallet.html.twig', array(
            'wallets' => $wallets
        ));
    }

    /**
     * @Route("/u/trade/wallets/{id}", name="trade_show")
     * @Method({"GET"})
     * @ParamConverter("tradingWallet", class="AppBundle:TradingWallet")
     */
    public function showAction(TradingWallet $tradingWallet, WalletManager $walletManager)
    {
        $session = $this->get('session');
        $session->set('current_wallet_id', $tradingWallet->getId());

        if (($this->getUser()->getId() !== $tradingWallet->getUser()->getId()) &&  $tradingWallet->getPublic() == 0) {
            throw new AccessDeniedException('Portefeuille privé: Le portefeuille est privé et ne vous appartient pas');
        }
        $totalCurrencies = $walletManager->getTotalCurrencyWalletValue($tradingWallet);
        return $this->render(':Trade:show.html.twig', array(
            'tradingWallet' => $tradingWallet, 'totalCurrencies' => $totalCurrencies
        ));
    }

    /**
     * @Route("/u/trade/wallets/{id}/history", name="trade_show_order_history")
     * @Method({"GET"})
     * @ParamConverter("tradingWallet", class="AppBundle:TradingWallet")
     */
    public function showOrderHistory(TradingWallet $tradingWallet)
    {
        $session = $this->get('session');
        $session->set('current_wallet_id', $tradingWallet->getId());
        if (($this->getUser()->getId() !== $tradingWallet->getUser()->getId()) &&  $tradingWallet->getPublic() == 0) {
            throw new AccessDeniedException('Portefeuille privé: Le portefeuille est privé et ne vous appartient pas');
        }

        return $this->render(':Trade:show_order.html.twig', [
            'tradingWallet' => $tradingWallet
        ]);
    }

    /**
     * @Route("/u/trade/new", name="trade_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tradingWallet = new TradingWallet();
        $tradingWallet->setPublic(true);
        $tradingWallet->setUser($this->getUser());

        $form = $this->createForm(TradingWalletType::class, $tradingWallet);
        $form->handleRequest($request);
        $tradingWallet->setEuroWallet($this->createEuroWallet($em, $tradingWallet, $form["initialAmount"]->getData()));

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tradingWallet);
            $em->flush();

            $this->addFlash('success-message', 'Votre portefeuille a bien été crée !');
            return $this->redirectToRoute('trade_index');
        }

        return $this->render(':Trade:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Create a new euro wallet by default
     */
    private function createEuroWallet($em, $tradingWallet, $amount)
    {
        $tradingWallet->setInitialAmount($amount);

        $euroWallet = new EuroWallet;
        $euroWallet->setAmount($amount);
        $euroWallet->setTradingWallet($tradingWallet);

        $em->persist($euroWallet);
        return $euroWallet;
    }

    /**
     * get ajax to remove wallet
     * @Route("/u/trade/delete/{id}", name="trade_delete", options = { "expose" = true })
     * @Method({"DELETE", "POST"})
     * @ParamConverter("tradingWallet", class="AppBundle:TradingWallet")
     */
    public function deleteAction(TradingWallet $tradingWallet)
    {
        if ($this->getUser()->getId() !== $tradingWallet->getUser()->getId()) {
            throw new AccessDeniedException('Access denied: It\'s not your wallet');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($tradingWallet);
        $em->flush();

        $this->addFlash('success-message', 'Votre portefeuille a bien été supprimé !');
        return $this->redirectToRoute('trade_index');
    }
}
