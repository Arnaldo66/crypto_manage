<?php

namespace App\Service;

use App\Entity\OrderAction;
use App\Entity\OrderMethod;
use App\Entity\TradingOrder;
use App\Entity\TradingWallet;
use App\Form\TradingOrderNextStepBuyLimitType;
use App\Form\TradingOrderNextStepSellLimitType;
use App\Form\TradingOrderNextStepSellType;
use App\Form\TradingOrderNextStepType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TradingOrderService
{
    /**
     * @var FormFactory
     */
    private $formFactory;
    /**
     * @var ObjectManager
     */
    private $entityManager;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    public function __construct(
        FormFactoryInterface $formFactory,
        ObjectManager $entityManager,
        SessionInterface $session,
        UrlGeneratorInterface $router
    ){
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @param TradingOrder  $entity
     * @param Request       $request
     * @param TradingWallet $tradingWallet
     *
     * @return FormInterface
     * @throws \Exception
     */
    public function getSpecificForm(TradingOrder $entity, Request $request): FormInterface
    {
        $params = $request->request->all();
        $class = null;
        if(isset($params['trading_order_next_step'])) {
            $class = TradingOrderNextStepType::class;
        } elseif (isset($params['trading_order_next_step_sell'])) {
            $class = TradingOrderNextStepSellType::class;
        } elseif (isset($params['trading_order_next_step_buy_limit'])) {
            $class = TradingOrderNextStepBuyLimitType::class;
        } elseif (isset($params['trading_order_next_step_sell_limit'])) {
            $class = TradingOrderNextStepSellLimitType::class;
        }

        if($class === null) {
            throw new \Exception('Cannot access to form call');
        }

        return $this->formFactory->create($class, $entity, ['wallet' => $this->getSessionWallet()]);
    }

    public function setOrderAction(Form $form, TradingOrder $entity): void
    {
        if ($form->getName() === 'trading_order_next_step' || $form->getName() === 'trading_order_next_step_buy_limit') {
            $order = $this->entityManager->getRepository(OrderAction::class)->findOneByName('Achat');
        } else {
            $order = $this->entityManager->getRepository(OrderAction::class)->findOneByName('Vente');
        }

        $entity->setOrderAction($order);
    }

    public function setOrderMethod(Form $form, TradingOrder $entity): void
    {
        if ($form->getName() === 'trading_order_next_step' || $form->getName() === 'trading_order_next_step_sell') {
            $method = $this->entityManager->getRepository(OrderMethod::class)->findOneByName('Market');
        } elseif($form->getName() === 'trading_order_next_step_buy_limit' || $form->getName() === 'trading_order_next_step_sell_limit') {
            $method = $this->entityManager->getRepository(OrderMethod::class)->findOneByName('Limit');
        }

        $entity->setOrderMethod($method);
    }


    /**
     * @param Form $form
     *
     * @return bool
     */
    public function isMarket(Form $form): bool
    {
        if ($form->getName() === 'trading_order_next_step' || $form->getName() === 'trading_order_next_step_sell') {
            return true;
        }

        return false;
    }

    /**
     * @return TradingWallet
     * @throws \Exception
     */
    public function getSessionWallet(): TradingWallet
    {
        $tradingWallet = $this->entityManager->getRepository(TradingWallet::class)->find(
            $this->session->get('current_wallet_id')
        );

        if($tradingWallet === null) {
            throw new \Exception('Cannot access to trade wallet in session');
        }

        return $tradingWallet;
    }

    /**
     * @param TradingOrder $tradingOrder
     *
     * @return array
     * @throws \Exception
     */
    public function generateTradingForm(TradingOrder $tradingOrder, ?Form $formInAction = null): array
    {
        $wallet = $this->getSessionWallet();
        $url = 'trade_order_new_final_step';

        $form = $this->formFactory->create(TradingOrderNextStepType::class, $tradingOrder, array(
            'wallet' => $wallet,
            'action' => $this->router->generate($url),
            'method' => 'POST',
        ));
        $formSell = $this->formFactory->create(TradingOrderNextStepSellType::class, $tradingOrder, array(
            'wallet' => $wallet,
            'action' => $this->router->generate($url),
            'method' => 'POST',
        ));

        $formBuyLimit = $this->formFactory->create(TradingOrderNextStepBuyLimitType::class, $tradingOrder, array(
            'wallet' => $wallet,
            'action' => $this->router->generate($url),
            'method' => 'POST',
        ));

        $formSellLimit = $this->formFactory->create(TradingOrderNextStepSellLimitType::class, $tradingOrder, array(
            'wallet' => $wallet,
            'action' => $this->router->generate($url),
            'method' => 'POST',
        ));

        $isMarket = true;
        if($formInAction !== null) {
            if($formInAction->getName() === 'trading_order_next_step') {
                $form = $formInAction;
            } elseif ($formInAction->getName() === 'trading_order_next_step_sell') {
                $formSell = $formInAction;
            } elseif ($formInAction->getName() === 'trading_order_next_step_buy_limit') {
                $formBuyLimit = $formInAction;
            } else {
                $formSellLimit = $formInAction;
            }
            $isMarket = $this->isMarket($formInAction);
        }

        return [
            'form' => $form,
            'formSell' => $formSell,
            'formBuyLimit' => $formBuyLimit,
            'formSellLimit' => $formSellLimit,
            'isMarket' => $isMarket
        ];
    }
}
