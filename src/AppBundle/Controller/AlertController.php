<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use AppBundle\Form\Type\AlertType;
use AppBundle\Entity\Alert;

class AlertController extends Controller
{
    /**
     * @Route("/u/alerts", name="alert")
     * @Method({"GET"})
     */
    public function alertsAction()
    {
        $alerts = $this->getDoctrine()->getManager()
                  ->getRepository('AppBundle:Alert')
                  ->findBy(array('user'=>$this->getUser()));
        return $this->render(':Alert:alerts.html.twig', array(
            'alerts' => $alerts
        ));
    }

    /**
     * @Route("/u/alerts/new", name="alert_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $alert = new Alert();
        $alert->setUser($this->getUser());
        $alert->setBuy(1);
        $form = $this->createForm(AlertType::class, $alert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($alert);
            $em->flush();

            $this->addFlash('success-message', 'Votre alerte a bien été crée');
            return $this->redirectToRoute('alert');
        }

        return $this->render(':Alert:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * get ajax to remove alert
     * @Route("/u/alert/delete/{id}", name="alerte_delete", options = { "expose" = true })
     * @Method({"DELETE", "POST"})
     * @ParamConverter("alert", class="AppBundle:Alert")
     */
    public function deleteAction(Alert $alert)
    {
        if ($this->getUser()->getId() !== $alert->getUser()->getId()) {
            throw new AccessDeniedException('Access denied: It\'s not your alert');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($alert);
        $em->flush();

        $this->addFlash('success-message', 'Votre alerte a bien été supprimée.');
        return $this->redirectToRoute('alert');
    }
}
