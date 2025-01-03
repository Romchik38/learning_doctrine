<?php

declare(strict_types=1);

use App\Infrastructure\Service\Math;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RetriveServiceTest extends KernelTestCase{
    public function testSomething(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $mathService = $container->get(Math::class);
        $sum = $mathService::sum(4,6);

        $this->assertEquals(10, $sum);
    }
}