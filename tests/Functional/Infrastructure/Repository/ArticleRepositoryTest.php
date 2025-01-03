<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Repository;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\Article\VO\Id;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleRepositoryTest extends KernelTestCase{
    private $entityManager;
    private ArticleRepositoryInterface $articleRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $container = $kernel->getContainer();

        $doctrine = $container->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        
        $this->articleRepository = $this->entityManager->getRepository(Article::class);
    }

    public function testGetById(): void{

        $article = $this->articleRepository->getById(new Id(1));

        $this->assertSame('Article1 name', $article->getName());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
