<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends Controller
{
    /**
     * @Route("/egbo/users", name="admin_users")
     * @Method({"GET"})
     */
    public function usersAction()
    {
        $users = $this->getDoctrine()->getManager()
                  ->getRepository('AppBundle:User')
                  ->findAll();

        return $this->render(':Admin\User:users.html.twig', array(
          'users' => $users
        ));
    }

}
