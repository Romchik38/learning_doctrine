<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api;

use App\Application\ArticleView\ArticleViewService;
use App\Infrastructure\Service\Math;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** Only for test */
final class MathController extends AbstractController
{

    #[Route('/math/sum/{a}/{b}', name: 'math_sum', methods: ['GET', 'HEAD'])]
    public function sum(int $a, int $b, Math $mathService): Response
    {
        return new Response(
            (string)$mathService::sum($a, $b)
        );
    }
}
