<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Entity\Article;

class ArticleController extends Controller
{
    /**
     * @Route("/articles", name="article_list")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $articles = $this->getDoctrine()->getManager()->getRepository(
          'AppBundle:Article')->findAll();
        return $this->render(':Article:index.html.twig', array(
            'articles' => $articles
        ));
    }

    /**
     * @Route("/articles/{slug}", name="article_show")
     * @Method({"GET"})
     * @ParamConverter("article", class="AppBundle:Article")
     */
    public function showAction(Article $article)
    {
        return $this->render(':Article:show.html.twig', array(
          'article' => $article
        ));
    }
}
