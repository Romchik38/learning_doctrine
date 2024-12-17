<?php

declare(strict_types=1);

namespace App\Domain\Article\VO;

use InvalidArgumentException;

final class ChangeActivity
{
    public const CHANGE_VALUE = 'yes';
    public const NO_CHANGE_VALUE = 'no';

    public readonly bool $changeActivity;

    public function __construct(
        string $change
    ) {
        if ($change === self::CHANGE_VALUE) {
            $this->changeActivity = true;
        } else {
            $this->changeActivity = false;
        }
    }

    public function __invoke(): bool
    {
        return $this->changeActivity;
    }
}
