<?php
namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render(
            'pages/index.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * @Route("/show/{articleId}", name="app_show")
     */
    public function show($articleId)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($articleId);

        return $this->render(
            'pages/show.html.twig',
            ['article' => $article]
        );
    }

    /**
     * @Route("/delete/{articleId}", name="app_delete")
     */
    public function delete($articleId)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($articleId);

        $em->remove($article);
        $em->flush();

        return new RedirectResponse('/');
    }

    /**
     * @Route("/update/{articleId}", name="app_update")
     */
    public function update(Request $request, $articleId)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($articleId);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return new RedirectResponse('/');
        }

        return $this->render(
            'pages/form.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/new", name="app_new")
     */
    public function createArticle(Request $request)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return new RedirectResponse('/');
        }

        return $this->render(
            'pages/form.html.twig',
            ['form' => $form->createView()]
        );
    }
}
