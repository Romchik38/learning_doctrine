<?php

declare(strict_types=1);

namespace App\Domain\Category\VO;

use InvalidArgumentException;

final class Id
{
    public function __construct(
        public readonly int $id
    ) {
        if ($id <= 0) {
            throw new InvalidArgumentException('id is invalid');
        }
    }

    public static function fromString(string $id): self
    {
        return new self((int) $id);
    }

    public function __invoke(): int
    {
        return $this->id;
    }

    public static function fromMixed($id): self
    {
        if (is_numeric($id)) {
            return new self((int) $id);
        } else {
            throw new InvalidArgumentException('id is invalid');
        }
    }
}
