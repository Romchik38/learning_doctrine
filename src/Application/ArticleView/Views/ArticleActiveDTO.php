<?php

declare(strict_types=1);

namespace App\Application\ArticleView\Views;

final class ArticleActiveDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $shortDescription,
    ) {}
}
