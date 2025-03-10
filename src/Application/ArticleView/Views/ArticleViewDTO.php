<?php

declare(strict_types=1);

namespace App\Application\ArticleView\Views;

final class ArticleViewDTO
{
    public readonly string $active;

    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $shortDescription,
        bool $active,
        public readonly CategoryViewDTO|null $category
    ) {
        $this->active = ($active === true) ? 'active' : 'not active';
    }
}
