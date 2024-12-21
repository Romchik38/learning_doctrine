<?php

namespace App\Infrastructure\Repository;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\Article\CouldNotDeleteException;
use App\Domain\Article\CouldNotSaveException;
use App\Domain\Article\NoSuchArticleException;
use App\Domain\Article\QueryException;
use App\Domain\Article\VO\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository implements ArticleRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        protected readonly EntityManagerInterface $entityManager,
        )
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $model): void {
        try {
            $this->entityManager->persist($model);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new CouldNotSaveException($e->getMessage());
        }
    }

    public function delete(Article $model): void {
        try {
            $this->entityManager->remove($model);
            $this->entityManager->flush();
        } catch(\Exception $e) {
            throw new CouldNotDeleteException($e->getMessage());
        }
    }

    public function getById(Id $id): Article {
        /** @var Article $model */
        $model = $this->find($id());
        if (is_null($model)) {
            throw new NoSuchArticleException(
                sprintf('article with id %d not found', $id())
            );
        }

        return $model;
    }

    public function getAll(): array {
        return $this->findAll();
    }

    public function getActive(): array {
        $query = $this->entityManager->createQuery(
            'SELECT a
            FROM App\Domain\Article\Article a
            WHERE a.active = :active'
        )->setParameter('active', 't');

        $models = $query->getResult();
        if(is_array($models)) {
            return $models;
        } else {
            return [];
        }
    }

    public function totalCount(): int {
        $query = $this->entityManager->createQuery(
            'SELECT COUNT(a.id) FROM App\Domain\Article\Article a'
        );
        try {
            $count = $query->getSingleScalarResult();
        } catch(NoResultException $e){
            throw new QueryException(
                sprintf('Cannot count articles, because of error: %s', $e->getMessage())
            );
        } catch(NonUniqueResultException $e){
            throw new QueryException(
                sprintf('Cannot count articles, because of error: %s', $e->getMessage())
            );
        } 

        $resultType = gettype($count);
        
        if($resultType === 'integer') {
            return $count;
        }

        throw new QueryException(
            sprintf('Unexpecting count result type: %s', $resultType)
        );
    }

}
