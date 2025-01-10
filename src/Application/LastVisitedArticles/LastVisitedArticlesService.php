<?php

declare(strict_types=1);

namespace App\Application\LastVisitedArticles;

use App\Application\LastVisitedArticles\Views\Article;

final class LastVisitedArticlesService
{
    public function __construct(
        protected readonly LastViewedArticleRepositoryInterface $repository
    ) {}

    /** @throws NoSuchArticleException */
    public function getLastArticle(): Article
    {
        return $this->repository->getLast();
    }

    /** @throws CouldNotSaveArticleException */
    public function saveLastArticle(Article $article): void {
        $this->repository->save($article);
    }
}
