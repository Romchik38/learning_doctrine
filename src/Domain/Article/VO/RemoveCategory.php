<?php

declare(strict_types=1);

namespace App\Domain\Article\VO;

final class RemoveCategory
{
    public const REMOVE_VALUE = 'yes';
    public const NOT_REMOVE_VALUE = 'no';

    public readonly bool $remove;

    public function __construct(
        string $remove
    ) {
        if ($remove === self::REMOVE_VALUE) {
            $this->remove = true;
        } else {
            $this->remove = false;
        }
    }

    public function __invoke(): bool
    {
        return $this->changeActivity;
    }
}
