<?php

declare(strict_types=1);

namespace App\Message;

use DateTime;

final class Homepage
{
    public function __construct(
        public readonly DateTime $date
    ) {}
}
