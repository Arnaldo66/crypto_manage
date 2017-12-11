<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\Type\ArticleType;
use AppBundle\Entity\Article;

class ArticleController extends Controller
{
    /**
     * @Route("/egbo/articles/new", name="admin_new_article")
     * @Method({"GET"})
     */
    public function newAction(Request $request)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          die('ii');
        }

        return $this->render(':Admin\Article:new.html.twig', array(
          'form'=>$form->createView()
        ));
    }

}
