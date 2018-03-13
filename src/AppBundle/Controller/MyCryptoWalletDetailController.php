<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use AppBundle\Entity\MyCryptoWallet;
use AppBundle\Entity\MyCryptoWalletDetail;
use AppBundle\Form\Type\MyCryptoWalletDetailType;

class MyCryptoWalletDetailController extends Controller
{
    /**
     * @Route("/u/my-crypto-detail/{id}/new" , name="my_crypto_detail_new")
     * @ParamConverter("myCryptoWallet", class="AppBundle:MyCryptoWallet")
     * @Method({"GET", "POST"})
     */
    public function newAction(MyCryptoWallet $myCryptoWallet, Request $request)
    {
        if ($myCryptoWallet->getUser()->getId() != $this->getUser()->getId()) {
            throw new AccessDeniedException('Access denied: It\'s not your wallet');
        }

        $walletDetail = new MyCryptoWalletDetail;
        $walletDetail->setMyCryptoWallet($myCryptoWallet);
        $form = $this->createForm(MyCryptoWalletDetailType::class, $walletDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($walletDetail);
            $em->flush();

            $this->addFlash('success-message', 'Vos cryptos ont bien été ajouté à votre wallet');
            return $this->redirectToRoute('my_crypto_show', array(
                'id'=> $myCryptoWallet->getId()
            ));
        }

        return $this->render(':MyCryptoWalletDetail:new.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/u/my-crypto-detail/delete/{id}")
     * @ParamConverter("myCryptoWalletDetail", class="AppBundle:MyCryptoWalletDetail")
     */
    public function deleteAction(MyCryptoWalletDetail $walletDetail)
    {
        return $this->render('AppBundle:MyCryptoWalletDetail:delete.html.twig', array(
            // ...
        ));
    }
}
