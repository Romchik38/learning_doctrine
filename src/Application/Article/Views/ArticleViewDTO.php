<?php

declare(strict_types=1);

namespace App\Application\Article\Views;

final class ArticleViewDTO
{
    public readonly string $active;

    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $shortDescription,
        bool $active,
    ) {
        $this->active = ($active === true) ? 'active' : 'not active';
    }
}
