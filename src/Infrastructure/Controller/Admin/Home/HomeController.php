<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Admin\Home;

use App\Application\ArticleView\ArticleViewService;
use App\Application\CategoryView\CategoryViewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class HomeController extends AbstractController
{
    public function __construct(
        protected readonly CategoryViewService $categoryViewService,
        protected readonly ArticleViewService $articleViewService
    )
    {
        
    }
    #[Route('/admin', name: 'admin_homepage_index', methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        $totalCategories = $this->categoryViewService->totalCount();
        $totalArticles = $this->articleViewService->totalCount();
        return $this->render('admin.html.twig', [
            'controller_name' => 'HomeController',
            'controller_template' => 'admin/home/index.html.twig',
            'total_categories' => $totalCategories,
            'total_articles' => $totalArticles,
        ]);
    }
}
