<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Form\Type\ArticleType;
use AppBundle\Entity\Article;

class ArticleController extends Controller
{


    /**
     * @Route("/egbo/articles/new", name="admin_new_article")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $article->setUser($this->getUser());
        $article->setVisible(1);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->persist($article);
          $em->flush();
          $this->addFlash('success-message','Votre article a bien été crée');
          return $this->redirectToRoute('article_list');
        }

        return $this->render(':Admin\Article:new_update.html.twig', array(
          'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/egbo/articles/{id}", name="admin_update_article")
     * @Method({"GET", "POST"})
     * @ParamConverter("article", class="AppBundle:Article")
     */
     public function updateAction(Request $request, Article $article){

       $article->setUser($this->getUser());
       $form = $this->createForm(ArticleType::class, $article);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
         $em = $this->getDoctrine()->getManager();
         $em->persist($article);
         $em->flush();
         $this->addFlash('success-message','Votre article a bien été modifié');
         return $this->redirectToRoute('article_list');
       }
       return $this->render(':Admin\Article:new_update.html.twig', array(
         'form'=>$form->createView()
       ));
     }

}
