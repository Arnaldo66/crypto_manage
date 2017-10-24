<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/egbo/users", name="admin_users")
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
