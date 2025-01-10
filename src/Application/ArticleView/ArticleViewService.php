<?php

declare(strict_types=1);

namespace App\Application\ArticleView;

use App\Application\ArticleView\Views\ArticleActiveDTO;
use App\Application\ArticleView\Views\ArticleViewDTO;
use App\Application\ArticleView\Views\CategoryViewDTO;
use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\Article\NoSuchArticleException;
use App\Domain\Article\QueryException;
use App\Domain\Article\VO\Id;
use App\Domain\Article\VO\Name;
use App\Domain\Article\VO\ShortDescription;

class ArticleViewService
{
    public function __construct(
        protected readonly ArticleRepositoryInterface $articleRepository
    ) {}

    /**
     * @throws NoSuchArticleException
     * 
     */
    public function find($id): ArticleViewDTO
    {
        $id = Id::fromMixed($id);
        /** @var Article $model */
        $model = $this->articleRepository->getById($id);
        if (is_null($model)) {
            throw new NoSuchArticleException(
                sprintf('article with id %d not found', $id())
            );
        }

        return $this->createArticleViewDTO($model);
    }

    public function findActive($id): ArticleViewDTO
    {
        $article = $this->find($id);
        if ($article->active === false) {
            throw new NoSuchArticleException('Article with id %s is not active');
        }
        return $article;
    }

    /**
     * @return array<int,ArticleViewDTO>
     */
    public function listAll(): array
    {
        $models = $this->articleRepository->getAll();
        $dtos = [];
        foreach ($models as $model) {
            $dtos[] = $this->createArticleViewDTO($model);
        }
        return $dtos;
    }

    /**
     * @return array<int,ArticleActiveDTO>
     */
    public function listActive(): array
    {
        $dtos = [];
        $models = $this->articleRepository->getActive();

        foreach ($models as $model) {
            $id = new Id($model->getId());
            $name = new Name($model->getName());
            $shortDescription = new ShortDescription($model->getShortDescription());
            $dtos[] = new ArticleActiveDTO(
                $id(),
                $name(),
                $shortDescription()
            );
        }

        return $dtos;
    }

    protected function createArticleViewDTO(Article $model): ArticleViewDTO
    {
        $id = new Id($model->getId());
        $name = new Name($model->getName());
        $shortDescription = new ShortDescription($model->getShortDescription());

        $category = $model->category();
        if (!is_null($category)) {
            $categoryId = $category->id();
            $categoryName = $category->name();
            $category =  new CategoryViewDTO(
                $categoryId(),
                $categoryName()
            );
        }
        return new ArticleViewDTO(
            $id(),
            $name(),
            $shortDescription(),
            $model->isActive(),
            $category
        );
    }

    /** 
     * @throws QueryException
     */
    public function totalCount(): int
    {
        return $this->articleRepository->totalCount();
    }
}
