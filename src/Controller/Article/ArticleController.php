<?php

namespace App\Controller\Article;

use App\Application\Article\AddFromForm;
use App\Application\Article\ArticleService;
use App\Application\Article\CantAddException;
use App\Application\Article\NoSuchArticleException;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{

    public function __construct(
        protected readonly ArticleService $articleService
    ) {}

    #[Route('/article/new', name: 'article_new_get', methods: ['GET', 'HEAD'])]
    public function new(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'ArticleController',
            'controller_template' => 'article/new.html.twig'
        ]);
    }

    #[Route('/article/save', name: 'article_save', methods: ['POST'])]
    public function save(Request $request): Response
    {
        $params = $request->request->all();
        try {
            $id = $this->articleService->add(AddFromForm::fromHash($params));
        } catch (CantAddException) {
            return new Response('article was not save, please try later');
        } catch (InvalidArgumentException $e) {
            return new Response(
                sprintf('Error while creating new article: %s', $e->getMessage())
            );
        }
        return new Response(
            sprintf('Article created with id: %s', $id())
        );
    }

    #[Route('/article/{id}', name: 'article_view', methods: ['GET', 'HEAD'])]
    public function view($id): Response
    {
        try {
            $articleDto = $this->articleService->find($id);
            return $this->render('base.html.twig', [
                'controller_name' => 'ArticleController',
                'controller_template' => 'article/view.html.twig',
                'article' => $articleDto
            ]);
        } catch (NoSuchArticleException) {
            throw $this->createNotFoundException(
                sprintf('article with id %s not found', $id)
            );
        } catch(InvalidArgumentException $e) {
            throw $this->createNotFoundException(
                sprintf('article with id %s not found', $id)
            );
        }
    }

    #[Route('/articles', name:'article_list', methods: ['GET', 'HEAD'])]
    public function list(): Response{
        $articles = $this->articleService->listAll();

        return $this->render('base.html.twig', [
            'controller_name' => 'ArticleController',
            'controller_template' => 'article/list.html.twig',
            'articles' => $articles
        ]); 
    }
}
