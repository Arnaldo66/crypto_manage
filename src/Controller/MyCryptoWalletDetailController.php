<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use App\Entity\MyCryptoWallet;
use App\Entity\MyCryptoWalletDetail;
use App\Form\MyCryptoWalletDetailType;

class MyCryptoWalletDetailController extends AbstractController
{
    /**
     * @Route("/u/my-crypto-detail/{id}/new" , name="my_crypto_detail_new", methods={"GET|POST"})
     * @ParamConverter("myCryptoWallet", class="App:MyCryptoWallet")
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

        return $this->render('MyCryptoWalletDetail/new.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/u/my-crypto-detail/delete/{id}", name="my_crypto_detail_delete", options = { "expose" = true }, methods={"DELETE", "POST"})
     * @ParamConverter("myCryptoWalletDetail", class="App:MyCryptoWalletDetail")
     */
    public function deleteAction(MyCryptoWalletDetail $walletDetail)
    {
        if ($this->getUser()->getId() !== $walletDetail->getMyCryptoWallet()->getUser()->getId()) {
            throw new AccessDeniedException('Access denied: It\'s not your alert');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($walletDetail);
        $em->flush();

        $this->addFlash('success-message', 'Votre crypto a bien été supprimée.');
        return $this->redirectToRoute('my_crypto_show', [
            'id'=> $walletDetail->getMyCryptoWallet()->getId()
        ]);
    }

    /**
     * @Route("/u/my-crypto-detail/add-amount/{id}", name="my_crypto_detail_add_amount", options = { "expose" = true }, methods={"DELETE", "POST"})
     */
    public function addAmount(Request $request, MyCryptoWalletDetail $walletDetail)
    {
        if ($this->getUser()->getId() !== $walletDetail->getMyCryptoWallet()->getUser()->getId()) {
            throw new AccessDeniedException('Access denied: It\'s not your alert');
        }

        $amount = $request->get('add_amount');
        if (!is_numeric($amount)) {
            $this->addFlash('error-message', 'Veuillez saisir une valeur numérique');
            return $this->redirectToRoute('my_crypto_show', [
                'id' => $walletDetail->getMyCryptoWallet()->getId()
            ]);
        }

        $walletDetail->setAmount($walletDetail->getAmount() + (float) $amount);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success-message', 'Votre montant a bien été augmenté.');
        return $this->redirectToRoute('my_crypto_show', [
            'id'=> $walletDetail->getMyCryptoWallet()->getId()
        ]);
    }

    /**
     * @Route("/u/my-crypto-detail/less-amount/{id}", name="my_crypto_detail_less_amount", options = { "expose" = true }, methods={"DELETE", "POST"})
     */
    public function lessAmount(Request $request, MyCryptoWalletDetail $walletDetail)
    {
        if ($this->getUser()->getId() !== $walletDetail->getMyCryptoWallet()->getUser()->getId()) {
            throw new AccessDeniedException('Access denied: It\'s not your alert');
        }

        $amount = $request->get('less_amount');
        if (!is_numeric($amount)) {
            $this->addFlash('error-message', 'Veuillez saisir une valeur numérique');
            return $this->redirectToRoute('my_crypto_show', [
                'id' => $walletDetail->getMyCryptoWallet()->getId()
            ]);
        }

        $walletDetail->setAmount($walletDetail->getAmount() - (float) $amount);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success-message', 'Votre montant a bien été diminué.');
        return $this->redirectToRoute('my_crypto_show', [
            'id'=> $walletDetail->getMyCryptoWallet()->getId()
        ]);
    }
}
