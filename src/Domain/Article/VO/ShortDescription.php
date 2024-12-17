<?php

declare(strict_types=1);

namespace App\Domain\Article\VO;

use InvalidArgumentException;

final class ShortDescription
{
    public const MAX_LENGTH = 250;

    public function __construct(
        public readonly string $shortDescription
    ) {
        $length = mb_strlen($shortDescription);
        if ( $length === 0) {
            throw new InvalidArgumentException('short description is empty');
        } elseif ($length > self::MAX_LENGTH) {
            throw new InvalidArgumentException('short description too long. Expected max 250 chars');
        }
    }

    public function __invoke(): string
    {
        return $this->shortDescription;
    }
}
