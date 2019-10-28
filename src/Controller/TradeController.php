<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use App\Form\TradingWalletType;
use App\Entity\TradingWallet;
use App\Entity\EuroWallet;
use App\Service\WalletManager;

class TradeController extends AbstractController
{
    /**
     * @Route("/u/trade/wallets", name="trade_index", methods={"GET"})
     */
    public function indexAction()
    {
        $wallets = $this->getUser()->getTradingWallets();

        return $this->render('Trade/index.html.twig', array(
            'wallets' => $wallets
        ));
    }

    /**
     * @Route("/portefeuilles-publics", name="trade_public", methods={"GET"})
     */
    public function publicWalletAction()
    {
        $em = $this->getDoctrine()->getManager();
        $wallets = $em->getRepository('App:TradingWallet')->findBy(array('public'=>1));

        return $this->render('Trade/public_wallet.html.twig', array(
            'wallets' => $wallets
        ));
    }

    /**
     * @Route("/u/trade/wallets/{id}", name="trade_show", methods={"GET"})
     * @ParamConverter("tradingWallet", class="App:TradingWallet")
     */
    public function showAction(TradingWallet $tradingWallet, WalletManager $walletManager)
    {
        $session = $this->get('session');
        $session->set('current_wallet_id', $tradingWallet->getId());

        if (($this->getUser()->getId() !== $tradingWallet->getUser()->getId()) &&  $tradingWallet->getPublic() == 0) {
            throw new AccessDeniedException('Portefeuille privé: Le portefeuille est privé et ne vous appartient pas');
        }
        $totalCurrencies = $walletManager->getTotalCurrencyWalletValue($tradingWallet);
        return $this->render('Trade/show.html.twig', array(
            'tradingWallet' => $tradingWallet, 'totalCurrencies' => $totalCurrencies
        ));
    }

    /**
     * @Route("/u/trade/wallets/{id}/history", name="trade_show_order_history", methods={"GET"})
     * @ParamConverter("tradingWallet", class="App:TradingWallet")
     */
    public function showOrderHistory(TradingWallet $tradingWallet)
    {
        $session = $this->get('session');
        $session->set('current_wallet_id', $tradingWallet->getId());
        if (($this->getUser()->getId() !== $tradingWallet->getUser()->getId()) &&  $tradingWallet->getPublic() == 0) {
            throw new AccessDeniedException('Portefeuille privé: Le portefeuille est privé et ne vous appartient pas');
        }

        return $this->render('Trade/show_order.html.twig', [
            'tradingWallet' => $tradingWallet
        ]);
    }

    /**
     * @Route("/u/trade/new", name="trade_new", methods={"GET|POST"})
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

        return $this->render('Trade/new.html.twig', array(
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
     * @Route("/u/trade/delete/{id}", name="trade_delete", options = { "expose" = true }, methods={"DELETE|POST"})
     * @ParamConverter("tradingWallet", class="App:TradingWallet")
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
