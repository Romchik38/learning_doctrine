<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Home;

use App\Application\ArticleView\ArticleViewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController{

    public function __construct(
        protected readonly ArticleViewService $articleViewService
    )
    {
        
    }

    #[Route('/', name: 'homepage_index', methods: ['GET', 'HEAD'])]
    public function index(): Response {
        $dtos =  $this->articleViewService->listActive();

        $continueReading = ['name' => 'Some name', 'link' => '#'];

        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
            'controller_template' => 'home/index.html.twig',
            'active_articles' => $dtos,
            'continue_reading' => $continueReading
        ]);
    }
}