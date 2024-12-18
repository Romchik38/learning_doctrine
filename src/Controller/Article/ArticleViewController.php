<?php

namespace App\Controller\Article;

use App\Application\Article\NoSuchArticleException;
use App\Application\ArticleView\ArticleViewService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleViewController extends AbstractController
{

    public function __construct(
        protected readonly ArticleViewService $articleViewService,
        protected UrlHelper $urlHelper,
    ) {}


    #[Route('/article/{id}', name: 'article_view', methods: ['GET', 'HEAD'])]
    public function view($id): Response
    {
        try {
            $articleDto = $this->articleViewService->find($id);
            return $this->render('base.html.twig', [
                'controller_name' => 'ArticleController',
                'controller_template' => 'article/view.html.twig',
                'article' => $articleDto
            ]);
        } catch (NoSuchArticleException) {
            throw $this->createNotFoundException(
                sprintf('article with id %s not found', $id)
            );
        } catch (InvalidArgumentException $e) {
            throw $this->createNotFoundException(
                sprintf('article with id %s not found', $id)
            );
        }
    }

    #[Route('/articles', name: 'article_list', methods: ['GET', 'HEAD'])]
    public function list(): Response
    {
        $articles = $this->articleViewService->listAll();

        return $this->render('base.html.twig', [
            'controller_name' => 'ArticleController',
            'controller_template' => 'article/list.html.twig',
            'articles' => $articles
        ]);
    }

    #[Route('/article/edit/{id}', name: 'article_edit', methods: ['GET', 'HEAD'])]
    public function edit($id): Response
    {
        try {
            $articleDto = $this->articleViewService->find($id);
            return $this->render('base.html.twig', [
                'controller_name' => 'ArticleController',
                'controller_template' => 'article/edit.html.twig',
                'article' => $articleDto
            ]);
        } catch (NoSuchArticleException) {
            throw $this->createNotFoundException(
                sprintf('article with id %s not found', $id)
            );
        } catch (InvalidArgumentException $e) {
            throw $this->createNotFoundException(
                sprintf('article with id %s not found', $id)
            );
        }
    }
}
