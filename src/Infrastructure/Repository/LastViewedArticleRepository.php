<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\LastVisitedArticles\LastViewedArticleRepositoryInterface;
use App\Application\LastVisitedArticles\NoSuchArticleException;
use App\Application\LastVisitedArticles\Views\Article;
use Symfony\Component\HttpFoundation\RequestStack;

final class LastViewedArticleRepository implements LastViewedArticleRepositoryInterface
{
    public const ARTICLE_NAME_FIELD = 'name';
    public const ARTICLE_LINK_FIELD = 'link';

    public function __construct(
        protected readonly RequestStack $requestStack
    ) {}

    /** @throws NoSuchArticleException */
    public function getLast(): Article
    {
        $session = $this->requestStack->getSession();
        $articleName = $session->get(self::ARTICLE_NAME_FIELD);
        $articleLink = $session->get(self::ARTICLE_LINK_FIELD);
        if (
            gettype($articleName) !== 'string'
            || gettype($articleLink) !== 'string'
        ) {
            throw new NoSuchArticleException('No last viewed article');
        }

        if (
            strlen($articleName) === 0
            || strlen($articleLink) === 0
        ) {
            throw new NoSuchArticleException('No last viewed article');
        }

        return new Article($articleName, $articleLink);
    }

    public function save(Article $article): void
    {
        $session = $this->requestStack->getSession();
        $session->set(self::ARTICLE_NAME_FIELD, $article->name);
        $session->set(self::ARTICLE_LINK_FIELD, $article->link);
    }
}
