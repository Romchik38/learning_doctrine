<?php

declare(strict_types=1);

namespace App\Domain\Article;

use App\Domain\Article\VO\Id;

interface ArticleRepositoryInterface
{
    /** @throws CouldNotDeleteException */
    public function delete(Article $model): void;

    /** @throws NoSuchArticleException */
    public function getById(Id $id): Article;

    /** @throws CouldNotSaveException */
    public function save(Article $model): void;
}
