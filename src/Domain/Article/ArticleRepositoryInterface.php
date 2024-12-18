<?php

declare(strict_types=1);

namespace App\Domain\Article;

interface ArticleRepositoryInterface
{
    /** @throws CouldNotSaveException */
    public function save(Article $model): void;
}
