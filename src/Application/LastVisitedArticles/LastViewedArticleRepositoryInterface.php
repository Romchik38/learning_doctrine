<?php

declare(strict_types=1);

namespace App\Application\LastVisitedArticles;

use App\Application\LastVisitedArticles\Views\Article;

interface LastViewedArticleRepositoryInterface {

    /** @throws NoSuchArticleException */
    public function getLast(): Article;

    /** @throws CouldNotSaveArticleException */
    public function save(Article $article): void;
}