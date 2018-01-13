<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Currency;
use AppBundle\Form\Type\CurrencyType;

class CurrencyController extends Controller
{
    /**
     * @Route("/egbo/currencies", name="admin_currencies")
     * @Method({"GET"})
     */
    public function currenciesAction()
    {
        $currencies = $this->getDoctrine()->getManager()
                      ->getRepository('AppBundle:Currency')
                      ->findAll();

        return $this->render(':Admin\Currency:currencies.html.twig', array(
            'currencies' => $currencies
        ));
    }

    /**
     * @Route("/egbo/currencies/{id}", name="admin_currencies_update")
     * @Method({"GET", "POST"})
     * @ParamConverter("currency", class="AppBundle:Currency")
     */
    public function updateAction(Request $request, Currency $currency){
      $form = $this->createForm(CurrencyType::class, $currency);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success-message','La crypto a bien été mise à jour');
        return $this->redirectToRoute('admin_currencies');
      }

      return $this->render(':Admin\Currency:update.html.twig', array(
        'form' => $form->createView(), 'entity' => $currency
      ));
    }
}
