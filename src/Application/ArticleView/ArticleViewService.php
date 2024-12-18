<?php

declare(strict_types=1);

namespace App\Application\ArticleView;

use App\Application\ArticleView\Views\ArticleActiveDTO;
use App\Application\ArticleView\Views\ArticleViewDTO;
use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\Article\NoSuchArticleException;
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

        $id = new Id($model->getId());
        $name = new Name($model->getName());
        $shortDescription = new ShortDescription($model->getShortDescription());

        return new ArticleViewDTO(
            $id(),
            $name(),
            $shortDescription(),
            $model->isActive()
        );
    }

    /**
     * @return array<int,ArticleViewDTO>
     */
    public function listAll(): array
    {
        $models = $this->articleRepository->getAll();
        $dtos = [];
        foreach ($models as $model) {
            $id = new Id($model->getId());
            $name = new Name($model->getName());
            $shortDescription = new ShortDescription($model->getShortDescription());
            $dtos[] = new ArticleViewDTO(
                $id(),
                $name(),
                $shortDescription(),
                $model->isActive()
            );
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
}
