<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Entity\MyCryptoWallet;
use AppBundle\Entity\MyCryptoWalletDetail;
use AppBundle\Form\Type\MyCryptoWalletDetailType;

class MyCryptoWalletDetailController extends Controller
{
    /**
     * @Route("/u/my-crypto-detail/new" , name="my_crypto_detail_new")
     * @Method({"GET", "POST"})
     */
    public function newAction()
    {
        //ajouter le formulaire, virer si pas currentUser, passer l'id du wallet en get
        return $this->render(':MyCryptoWalletDetail:new.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/u/my-crypto-detail/delete/{id}")
     */
    public function deleteAction($id)
    {
        return $this->render('AppBundle:MyCryptoWalletDetail:delete.html.twig', array(
            // ...
        ));
    }

}
