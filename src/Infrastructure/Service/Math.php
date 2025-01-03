<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

/** for test */
final class Math
{
    public static function sum(int $a, int $b): int
    {
        return $a + $b;
    }
}
