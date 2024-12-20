<?php

namespace App\Infrastructure\Repository;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepositoryInterface;
use App\Domain\Category\CouldNotDeleteException;
use App\Domain\Category\CouldNotSaveException;
use App\Domain\Category\NoSuchCategoryException;
use App\Domain\Category\VO\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        protected readonly EntityManagerInterface $entityManager,
        )
    {
        parent::__construct($registry, Category::class);
    }

    public function save(Category $model): void {
        try {
            $this->entityManager->persist($model);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new CouldNotSaveException($e->getMessage());
        }
    }

    public function delete(Category $model): void {
        try {
            $this->entityManager->remove($model);
            $this->entityManager->flush();
        } catch(\Exception $e) {
            throw new CouldNotDeleteException($e->getMessage());
        }
    }

    public function getById(Id $id): Category {
        /** @var Category $model */
        $model = $this->find($id());
        if (is_null($model)) {
            throw new NoSuchCategoryException(
                sprintf('category with id %d not found', $id())
            );
        }

        return $model;
    }

    public function getAll(): array {
        return $this->findAll();
    }

    /** @todo refactor to SQL */
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
}
