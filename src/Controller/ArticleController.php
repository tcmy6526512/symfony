<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/creer', name: 'app_article_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setDate(new \DateTimeImmutable());
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_list');
        }

        return $this->render('article/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/liste', name: 'app_article_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->render('article/liste.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}/lire', name: 'app_article_show', requirements: ['id' => '\d+'])]
    public function show(Article $article): Response
    {
        return $this->render('article/lire.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/{id}/miseajour', name: 'app_article_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_list');
        }

        return $this->render('article/miseajour.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}/supprimer', name: 'app_article_delete', requirements: ['id' => '\d+'])]
    public function delete(Article $article, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_article_list');
    }
}
