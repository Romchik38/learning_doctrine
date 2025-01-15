<?php

declare(strict_types=1);

use App\Application\ArticleView\ArticleViewService;
use App\Application\ArticleView\Views\ArticleActiveDTO;
use App\Application\LastVisitedArticles\LastVisitedArticlesService;
use App\Domain\Article\Article;
use App\Infrastructure\Controller\Home\HomeController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MockingRepositoryTest extends KernelTestCase
{
    public function testHomePage()
    {
        self::bootKernel();
        $container = static::getContainer();

        $articleService = $this->createMock(ArticleViewService::class);
        // $lastVisitedArticlesService = $this->createMock(LastVisitedArticlesService::class);
        $article1 = new ArticleActiveDTO(
            1,
            'Some article',
            'Some short description'
        );

        $articleService->expects($this->once())->method('listActive')
            ->willReturn([
                $article1,
            ]);
        
        // $lastVisitedArticlesService->method('')
        
        $container->set(ArticleViewService::class, $articleService);
        $homeController = $container->get(HomeController::class);
        $homeController->index();
    }
}
