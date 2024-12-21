<?php

declare(strict_types=1);

namespace App\Application\CategoryView;

use App\Application\CategoryView\Views\CategoryViewDTO;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepositoryInterface;
use App\Domain\Category\NoSuchCategoryException;
use App\Domain\Category\VO\Id;

final class CategoryViewService
{
    public function __construct(
        protected readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * @return array<int,CategoryViewDTO>
     */
    public function listAll(): array
    {
        $models = $this->categoryRepository->getAll();
        $dtos = [];
        foreach ($models as $model) {
            $id = $model->id();
            $name = $model->name();
            $dtos[] = new CategoryViewDTO(
                $id(),
                $name()
            );
        }
        return $dtos;
    }

    /**
     * @throws NoSuchCategoryException
     * 
     */
    public function find($key): CategoryViewDTO
    {
        $id = Id::fromMixed($key);
        /** @var Category $model */
        $model = $this->categoryRepository->getById($id);
        if (is_null($model)) {
            throw new NoSuchCategoryException(
                sprintf('category with id %d not found', $id())
            );
        }

        $id = $model->id();
        $name = $model->name();

        return new CategoryViewDTO(
            $id(),
            $name()
        );
    }
}
