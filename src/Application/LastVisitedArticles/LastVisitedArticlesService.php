<?php

declare(strict_types=1);

namespace App\Application\LastVisitedArticles;

use App\Application\LastVisitedArticles\Views\Article;

final class LastVisitedArticlesService {

    /** @throws NoSuchArticleException */
    public function getLastArticle(): Article {
        return new Article(
            'Some name1',
            '#'
        );
    }
}