<?php

declare(strict_types=1);

namespace App\Application\LastVisitedArticles\Views;

final class Article
{
    public function __construct(
        public readonly string $name,
        public readonly string $link
    ) {}
}
