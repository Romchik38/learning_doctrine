<?php

declare(strict_types=1);

namespace App\Event;

use DateTime;
use Symfony\Contracts\EventDispatcher\Event;

final class HomePage extends Event
{
    public function __construct(
        public DateTime $date
    ) {}
}
