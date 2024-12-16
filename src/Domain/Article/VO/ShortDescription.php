<?php

declare(strict_types=1);

namespace App\Domain\Article\VO;

use InvalidArgumentException;

final class ShortDescription
{
    public function __construct(
        public readonly string $shortDescription
    ) {
        $length = strlen($shortDescription);
        if ( $length === 0) {
            throw new InvalidArgumentException('short description is empty');
        } elseif ($length > 100) {
            throw new InvalidArgumentException('short description too long. Expected max 100 chars');
        }
    }

    public function __invoke(): string
    {
        return $this->shortDescription;
    }
}
