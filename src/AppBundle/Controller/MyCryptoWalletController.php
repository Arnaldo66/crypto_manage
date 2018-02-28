<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Entity\MyCryptoWallet;
use AppBundle\Form\Type\MyCryptoWalletType;


class MyCryptoWalletController extends Controller
{
    /**
     * @Route("/u/my-crypto/dashboard", name="my_crypto_dashboard")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $crypto_wallets = $this->getUser()->getMyCryptoWallets();
        return $this->render(':MyCryptoWallet:dashboard.html.twig', array(
            'crypto_wallets' => $crypto_wallets
        ));
    }

    /**
     * @Route("/u/my-crypto/new", name="my_crypto_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $myCryptoWallet = new MyCryptoWallet();
        $myCryptoWallet->setUser($this->getUser());
        $form = $this->createForm(MyCryptoWalletType::class, $myCryptoWallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->persist($myCryptoWallet);
          $em->flush();

          $this->addFlash('success-message','Votre portefeuille de suivi a bien été crée');
          return $this->redirectToRoute('my_crypto_dashboard');
        }
        return $this->render(':MyCryptoWallet:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/u/my-crypto/{id}", name="my_crypto_show")
     * @ParamConverter("myCryptoWallet", class="AppBundle:MyCryptoWallet")
     */
    public function showAction(MyCryptoWallet $myCryptoWallet)
    {
        return $this->render(':MyCryptoWallet:show.html.twig', array(
          'myCryptoWallet' => $myCryptoWallet
        ));
    }

}
