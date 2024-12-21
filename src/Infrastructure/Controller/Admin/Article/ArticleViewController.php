<?php

namespace App\Infrastructure\Controller\Admin\Article;

use App\Application\ArticleView\ArticleViewService;
use App\Domain\Article\NoSuchArticleException;
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


    #[Route('/admin/article/{id}', name: 'admin_article_view', methods: ['GET', 'HEAD'])]
    public function view($id): Response
    {
        try {
            $articleDto = $this->articleViewService->find($id);
            return $this->render('admin.html.twig', [
                'controller_name' => 'ArticleController',
                'controller_template' => 'admin/article/view.html.twig',
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

    #[Route('/admin/article', name: 'admin_article_list', methods: ['GET', 'HEAD'])]
    public function list(): Response
    {
        $articles = $this->articleViewService->listAll();

        return $this->render('admin.html.twig', [
            'controller_name' => 'ArticleController',
            'controller_template' => 'admin/article/list.html.twig',
            'articles' => $articles
        ]);
    }

    #[Route('/admin/article/edit/{id}', name: 'admin_article_edit', methods: ['GET', 'HEAD'])]
    public function edit($id): Response
    {
        try {
            $articleDto = $this->articleViewService->find($id);
            return $this->render('admin.html.twig', [
                'controller_name' => 'ArticleController',
                'controller_template' => 'admin/article/edit.html.twig',
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
