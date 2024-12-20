<?php

declare(strict_types=1);

namespace App\Domain\Category\VO;

use InvalidArgumentException;

final class Name
{
    public function __construct(
        public readonly string $name
    ) {
        if (strlen($name) === 0) {
            throw new InvalidArgumentException('name is empty');
        }
    }

    public function __invoke(): string
    {
        return $this->name;
    }
}
