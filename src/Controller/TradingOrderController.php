<?php

namespace App\Controller;

use App\Entity\OrderStatus;
use App\Service\TradingOrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\TradingOrderFirstStepType;
use App\Entity\TradingOrder;
use App\Service\WalletManager;

class TradingOrderController extends AbstractController
{
    /**
     * @var TradingOrderService
     */
    private $tradeOrderService;

    public function __construct(TradingOrderService $tradeOrderService)
    {
        $this->tradeOrderService = $tradeOrderService;
    }

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
            if($this->tradeOrderService->isMarket($form)) {
                $tradeOrder->setPrice($currency->getPriceEur());
            }

            $arrayForm = $this->tradeOrderService->generateTradingForm($tradeOrder);

            return $this->render('TradingOrder/new-next-step.html.twig', array(
                'form'=> $arrayForm['form']->createView(), 'formSell' => $arrayForm['formSell']->createView(),
                'currency' => $currency, 'formBuyLimit' => $arrayForm['formBuyLimit']->createView(),
                'formSellLimit' => $arrayForm['formSellLimit']->createView(), 'isMarket' => $arrayForm['isMarket'],
                'wallet' =>$this->tradeOrderService->getSessionWallet(), 'errors'=> 0, 'usabledAmount' => $this->tradeOrderService->getUsabledAmount()
            ));
        }
    }

    /**
     * @Route("/u/trade/order/new/final-step", name="trade_order_new_final_step", methods={"POST"})
     */
    public function newFinalStepAction(Request $request, WalletManager $walletManager)
    {
        $tradeOrder = new TradingOrder;
        $form = $this->tradeOrderService->getSpecificForm($tradeOrder, $request);
        $form->handleRequest($request);
        $errors = 0;

        $this->tradeOrderService->setOrderAction($form, $tradeOrder);
        $this->tradeOrderService->setOrderMethod($form, $tradeOrder);

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

        $arrayForm = $this->tradeOrderService->generateTradingForm($tradeOrder, $form);

        return $this->render('TradingOrder/new-next-step.html.twig', array(
            'form'=> $arrayForm['form']->createView(), 'currency' => $tradeOrder->getCurrency(),
            'formSell' => $arrayForm['formSell']->createView(), 'formBuyLimit' => $arrayForm['formBuyLimit']->createView(),
            'formSellLimit' => $arrayForm['formSellLimit']->createView(), 'isMarket' => $arrayForm['isMarket'],
            'wallet' => $tradeOrder->getTradingWallet(), 'errors'=> $errors, 'entity' => $tradeOrder,
            'usabledAmount' => $this->tradeOrderService->getUsabledAmount()
        ));
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
