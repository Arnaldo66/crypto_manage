<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\MyCryptoWallet;
use App\Form\MyCryptoWalletType;

class MyCryptoWalletController extends AbstractController
{
    /**
     * @Route("/u/my-crypto/dashboard", name="my_crypto_dashboard", methods={"GET"})
     */
    public function indexAction()
    {
        $cryptoWallets = $this->getUser()->getMyCryptoWallets();
        return $this->render('MyCryptoWallet/dashboard.html.twig', array(
            'crypto_wallets' => $cryptoWallets
        ));
    }

    /**
     * @Route("/u/my-crypto/new", name="my_crypto_new", methods={"GET|POST"})
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

            $this->addFlash('success-message', 'Votre portefeuille de suivi a bien été crée');
            return $this->redirectToRoute('my_crypto_dashboard');
        }
        return $this->render('MyCryptoWallet/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/u/my-crypto/{id}", name="my_crypto_show", methods={"GET"})
     * @ParamConverter("myCryptoWallet", class="App:MyCryptoWallet")
     */
    public function showAction(MyCryptoWallet $myCryptoWallet)
    {
        return $this->render('MyCryptoWallet/show.html.twig', array(
          'myCryptoWallet' => $myCryptoWallet
        ));
    }
}
