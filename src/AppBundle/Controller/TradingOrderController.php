<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Form\Type\TradingOrderFirstStepType;
use AppBundle\Form\Type\TradingOrderNextStepType;
use AppBundle\Entity\TradingOrder;
use AppBundle\Service\WalletManager;

class TradingOrderController extends Controller
{
    /**
     * @Route("/u/trade/order/new", name="trade_order_new")
     * @Method({"GET"})
     */
    public function newAction()
    {
        $tradeOrder = new TradingOrder;
        $form = $this->createForm(TradingOrderFirstStepType::class, $tradeOrder, array(
          'action' => $this->generateUrl('trade_order_new_next_step'),
          'method' => 'POST',
        ));

        return $this->render(':TradingOrder:new-first-step.html.twig', array(
          'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/u/trade/order/new/next-step", name="trade_order_new_next_step")
     * @Method({"POST"})
     */
    public function newNextStepAction(Request $request)
    {
        $tradeOrder = new TradingOrder;
        $form = $this->createForm(TradingOrderFirstStepType::class, $tradeOrder);
        $form->handleRequest($request);

        $currency = $tradeOrder->getCurrency();
        if ($currency === null) {
            $this->addFlash('error-message', 'Veuillez renseigner une crypto-monnaie');
            return $this->redirectToRoute('trade_order_new');
        }

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $orderAction = $em->getRepository('AppBundle:OrderAction')->find(1);
            $orderMethod = $em->getRepository('AppBundle:OrderMethod')->find(1);
            $tradeOrder->setOrderAction($orderAction);
            $tradeOrder->setOrderMethod($orderMethod);
            $wallet = $this->getSessionWallet();

            $form = $this->createForm(TradingOrderNextStepType::class, $tradeOrder, array(
                'wallet' => $wallet,
                'action' => $this->generateUrl('trade_order_new_final_step'),
                'method' => 'POST',
            ));
            return $this->render(':TradingOrder:new-next-step.html.twig', array(
                'form'=> $form->createView(), 'currency' => $currency,
                'wallet' =>$wallet, 'errors'=> 0
            ));
        }
    }

    /**
     * @Route("/u/trade/order/new/final-step", name="trade_order_new_final_step")
     * @Method({"POST"})
     */
    public function newFinalStepAction(Request $request, WalletManager $walletManager)
    {
        $tradeOrder = new TradingOrder;

        $form = $this->createForm(
            TradingOrderNextStepType::class,
            $tradeOrder,
            array('wallet' => $this->getSessionWallet())
        );
        $form->handleRequest($request);
        $errors = 0;

        if ($form->isSubmitted() && $form->isValid()) {
            if ($tradeOrder->getTradingWallet()->getUser()->getId() !== $this->getUser()->getId()) {
                throw new AccessDeniedException('Ce portefeuille ne vous appartient pas');
            }

            $canFinalise = $walletManager->canFinaliseOrder($tradeOrder);
            if ($canFinalise['success']) {
                $walletManager->finaliseOrder($tradeOrder);

                $this->addFlash('success-message', 'L\'ordre a bien été enregistré');
                return $this->redirectToRoute('trade_show', array('id'=>$tradeOrder->getTradingWallet()->getId()));
            } else {
                $this->addFlash('error-message', $canFinalise['message']);
                $errors = 1;
            }
        }
        return $this->render(':TradingOrder:new-next-step.html.twig', array(
            'form'=> $form->createView(), 'currency' => $tradeOrder->getCurrency(),
            'wallet' => $tradeOrder->getTradingWallet(), 'errors'=> $errors, 'entity' => $tradeOrder
        ));
    }

    /**
     * get wallet in session
     */
    private function getSessionWallet()
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $id = $session->get('current_wallet_id');

        return $em->getRepository('AppBundle:TradingWallet')->find($id);
    }


    /**
      * get ajax to cancel order
      * @Route("/u/trade/order/cancel/{id}", name="trade_order_cancel", options = { "expose" = true })
      * @Method({"PUT", "POST"})
      * @ParamConverter("tradingOrder", class="AppBundle:TradingOrder")
     */
    public function deleteAction(TradingOrder $tradingOrder)
    {
        if ($tradingOrder->getOrderStatus()->getId() != $this->getParameter('order_status_pending')) {
            throw new AccessDeniedException('Access denied: It\'s not possible to cancel this order');
        }

        if ($this->getUser()->getId() !== $tradingOrder->getTradingWallet()->getUser()->getId()) {
            throw new AccessDeniedException('Access denied: It\'s not your wallet');
        }

        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository('AppBundle:orderStatus')
            ->find($this->getParameter('order_status_abort'));
        $tradingOrder->setOrderStatus($status);
        $em->flush();

        $this->addFlash('success-message', 'Votre ordre a bien été annulé !');
        return $this->redirectToRoute('trade_show', array('id'=>$tradingOrder->getTradingWallet()->getId()));
    }
}
