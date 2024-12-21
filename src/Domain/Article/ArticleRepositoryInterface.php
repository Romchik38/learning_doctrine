<?php

declare(strict_types=1);

namespace App\Domain\Article;

use App\Domain\Article\VO\Id;

interface ArticleRepositoryInterface
{
    /** @throws CouldNotDeleteException */
    public function delete(Article $model): void;

    /** @return array<int,Article> */
    public function getActive(): array;

    /** @return array<int,Article> */
    public function getAll(): array;

    /** @throws NoSuchArticleException */
    public function getById(Id $id): Article;

    /** @throws CouldNotSaveException */
    public function save(Article $model): void;

    /** @throws QueryException */
    public function totalCount(): int;
}
