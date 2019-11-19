<?php

namespace App\Controller;

use App\Entity\OrderStatus;
use App\Entity\TradingWallet;
use App\Form\TradingOrderNextStepBuyLimitType;
use App\Form\TradingOrderNextStepSellLimitType;
use App\Form\TradingOrderNextStepSellType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\TradingOrderFirstStepType;
use App\Form\TradingOrderNextStepType;
use App\Entity\TradingOrder;
use App\Service\WalletManager;
use App\Entity\OrderAction;
use App\Entity\OrderMethod;

class TradingOrderController extends AbstractController
{
    /**
     * @Route("/u/trade/order/new", name="trade_order_new", methods={"GET"})
     */
    public function newAction()
    {
        $tradeOrder = new TradingOrder;
        $form = $this->createForm(TradingOrderFirstStepType::class, $tradeOrder, array(
          'action' => $this->generateUrl('trade_order_new_next_step'),
          'method' => 'POST',
        ));

        return $this->render('TradingOrder/new-first-step.html.twig', array(
          'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/u/trade/order/new/next-step", name="trade_order_new_next_step", methods={"POST"})
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
            $wallet = $this->getSessionWallet();
            if($this->isMarket($form)) {
                $tradeOrder->setPrice($currency->getPriceEur());
            }

            $form = $this->createForm(TradingOrderNextStepType::class, $tradeOrder, array(
                'wallet' => $wallet,
                'action' => $this->generateUrl('trade_order_new_final_step'),
                'method' => 'POST',
            ));
            $formSell = $this->createForm(TradingOrderNextStepSellType::class, $tradeOrder, array(
                'wallet' => $wallet,
                'action' => $this->generateUrl('trade_order_new_final_step'),
                'method' => 'POST',
            ));

            $formBuyLimit = $this->createForm(TradingOrderNextStepBuyLimitType::class, $tradeOrder, array(
                'wallet' => $wallet,
                'action' => $this->generateUrl('trade_order_new_final_step'),
                'method' => 'POST',
            ));

            $formSellLimit = $this->createForm(TradingOrderNextStepSellLimitType::class, $tradeOrder, array(
                'wallet' => $wallet,
                'action' => $this->generateUrl('trade_order_new_final_step'),
                'method' => 'POST',
            ));

            return $this->render('TradingOrder/new-next-step.html.twig', array(
                'form'=> $form->createView(), 'formSell' => $formSell->createView(),
                'currency' => $currency, 'formBuyLimit' => $formBuyLimit->createView(),
                'formSellLimit' => $formSellLimit->createView(),
                'wallet' =>$wallet, 'errors'=> 0
            ));
        }
    }

    /**
     * @Route("/u/trade/order/new/final-step", name="trade_order_new_final_step", methods={"POST"})
     */
    public function newFinalStepAction(Request $request, WalletManager $walletManager)
    {
        $tradeOrder = new TradingOrder;
        $form = $this->getSpecificForm($tradeOrder, $request);
        $form->handleRequest($request);
        $errors = 0;

        $this->setOrderAction($form, $tradeOrder);
        $this->setOrderMethod($form, $tradeOrder);

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

        //TODO: currency not working is null
        return $this->render('TradingOrder/new-next-step.html.twig', array(
            'form'=> $form->createView(), 'currency' => $tradeOrder->getCurrency(),
            'wallet' => $tradeOrder->getTradingWallet(), 'errors'=> $errors, 'entity' => $tradeOrder
        ));
    }

    /**
     * @param Form $form
     *
     * @return bool
     */
    private function isMarket(Form $form): bool
    {
        if ($form->getName() === 'trading_order_next_step' || 'trading_order_next_step_sell') {
            return true;
        }

        return false;
    }

    /**
     * @param TradingOrder $entity
     *
     * @return FormInterface
     * @throws \Exception
     */
    private function getSpecificForm(TradingOrder $entity, Request $request): FormInterface
    {
        $params = $request->request->all();
        if(isset($params['trading_order_next_step'])) {
            return $this->createForm(
                TradingOrderNextStepType::class,
                $entity,
                ['wallet' => $this->getSessionWallet()]
            );
        }
        if(isset($params['trading_order_next_step_sell'])) {
            return $this->createForm(
                TradingOrderNextStepSellType::class,
                $entity,
                ['wallet' => $this->getSessionWallet()]
            );
        }

        if(isset($params['trading_order_next_step_buy_limit'])) {
            return $this->createForm(
                TradingOrderNextStepBuyLimitType::class,
                $entity,
                ['wallet' => $this->getSessionWallet()]
            );
        }

        if(isset($params['trading_order_next_step_sell_limit'])) {
            return $this->createForm(
                TradingOrderNextStepSellLimitType::class,
                $entity,
                ['wallet' => $this->getSessionWallet()]
            );
        }

        throw new \Exception('nothing is good');
    }

    private function setOrderAction(Form $form, TradingOrder $entity): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->getName() === 'trading_order_next_step' || $form->getName() === 'trading_order_next_step_buy_limit') {
            $order = $entityManager->getRepository(OrderAction::class)->findOneByName('Achat');
        } else {
            $order = $entityManager->getRepository(OrderAction::class)->findOneByName('Vente');
        }

        $entity->setOrderAction($order);
    }

    private function setOrderMethod(Form $form, TradingOrder $entity): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->getName() === 'trading_order_next_step' || $form->getName() === 'trading_order_next_step_sell') {
            $method = $entityManager->getRepository(OrderMethod::class)->findOneByName('Market');
        } elseif($form->getName() === 'trading_order_next_step_buy_limit' || $form->getName() === 'trading_order_next_step_sell_limit') {
            $method = $entityManager->getRepository(OrderMethod::class)->findOneByName('Limit');
        }

        $entity->setOrderMethod($method);
    }


    /**
     * get wallet in session
     */
    private function getSessionWallet()
    {
        $session = $this->get('session');
        $id = $session->get('current_wallet_id');

        return $this->getDoctrine()->getManager()->getRepository(TradingWallet::class)->find($id);
    }


    /**
      * get ajax to cancel order
      * @Route("/u/trade/order/cancel/{id}", name="trade_order_cancel", options = { "expose" = true }, methods={"PUT|POST|DELETE"})
      * @ParamConverter("tradingOrder", class="App:TradingOrder")
     */
    public function deleteAction(TradingOrder $tradingOrder)
    {
        if ($tradingOrder->getOrderStatus()->getName() !== 'En cours') {
            throw new AccessDeniedException('Access denied: It\'s not possible to cancel this order');
        }

        if ($this->getUser()->getId() !== $tradingOrder->getTradingWallet()->getUser()->getId()) {
            throw new AccessDeniedException('Access denied: It\'s not your wallet');
        }

        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository(OrderStatus::class)
            ->findOneByName('Annulé');
        $tradingOrder->setOrderStatus($status);
        $em->flush();

        $this->addFlash('success-message', 'Votre ordre a bien été annulé !');
        return $this->redirectToRoute('trade_show', array('id'=>$tradingOrder->getTradingWallet()->getId()));
    }
}
