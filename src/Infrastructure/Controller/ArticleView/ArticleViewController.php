<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\ArticleView;

use App\Application\ArticleView\ArticleViewService;
use App\Application\LastVisitedArticles\CouldNotSaveArticleException;
use App\Application\LastVisitedArticles\LastVisitedArticlesService;
use App\Application\LastVisitedArticles\Views\Article;
use App\Domain\Article\NoSuchArticleException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleViewController extends AbstractController
{

    public function __construct(
        protected readonly ArticleViewService $articleViewService,
        protected readonly LastVisitedArticlesService $lastVisitedArticlesService,
        protected readonly LoggerInterface $logger
    ) {}

    #[Route('/article/{id}', name: 'article_view', methods: ['GET', 'HEAD'])]
    public function view($id): Response
    {
        try {
            $articleDto = $this->articleViewService->findActive($id);

            try {
                $this->lastVisitedArticlesService->saveLastArticle(
                    new Article(
                        $articleDto->name,
                        $this->generateUrl('article_view', ['id' => $id])
                    )
                );
            } catch (CouldNotSaveArticleException) {
                $this->logger->error(
                    sprintf(
                        'Last viewed article service did not save article with id %s',
                        $id
                    )
                );
            }

            return $this->render('base.html.twig', [
                'controller_name' => 'ArticleController',
                'controller_template' => 'home/article/view.html.twig',
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
