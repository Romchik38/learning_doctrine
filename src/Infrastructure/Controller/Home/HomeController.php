<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Home;

use App\Application\ArticleView\ArticleViewService;
use App\Application\LastVisitedArticles\LastVisitedArticlesService;
use App\Application\LastVisitedArticles\NoSuchArticleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController
{

    public function __construct(
        protected readonly ArticleViewService $articleViewService,
        protected readonly LastVisitedArticlesService $lastVisitedArticlesService
    ) {}

    #[Route('/', name: 'homepage_index', methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        $dtos =  $this->articleViewService->listActive();

        try {
            $lastViewedArticle = $this->lastVisitedArticlesService->getLastArticle();
        } catch (NoSuchArticleException) {
            $lastViewedArticle = null;
        }

        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
            'controller_template' => 'home/index.html.twig',
            'active_articles' => $dtos,
            'continue_reading' => $lastViewedArticle
        ]);
    }
}
