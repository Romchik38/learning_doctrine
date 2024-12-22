<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Repository;

use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\Article\VO\Id;
use App\Infrastructure\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleRepositoryTest extends KernelTestCase{
    private EntityManager $entityManager;
    private ArticleRepositoryInterface $articleRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        $this->articleRepository = $this->entityManager->getRepository(ArticleRepository::class);
    }

    public function testGetById(): void{

        $article = $this->articleRepository->getById(new Id(6));

        $this->assertSame('Пригоди Луні Пака на Марсі', $article->getName());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
