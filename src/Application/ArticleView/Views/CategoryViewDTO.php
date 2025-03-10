<?php

declare(strict_types=1);

namespace App\Application\ArticleView\Views;

final class CategoryViewDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {}
}
