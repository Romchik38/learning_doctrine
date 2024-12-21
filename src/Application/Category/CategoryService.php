<?php

declare(strict_types=1);

namespace App\Application\Category;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepositoryInterface;
use App\Domain\Category\CouldNotDeleteException;
use App\Domain\Category\CouldNotSaveException;
use App\Domain\Category\NoSuchCategoryException;
use App\Domain\Category\VO\Id;
use App\Domain\Category\VO\Name;

final class CategoryService
{
    public function __construct(
        protected readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * @throws NoSuchCategoryException
     * @throws CouldNotSaveException
     * @throws InvalidArgumentException
     */
    public function save(AddFromForm $command): Id
    {
        $name = new Name($command->name);

        if (strlen($command->id) > 0) {
            $id = Id::fromString($command->id);
            $model = $this->categoryRepository->getById($id);
            $model->reName($name);
        } else {
            $model = new Category($name);
        }

        $this->categoryRepository->save($model);
        return $model->id();
    }

    /**
     * @throws NoSuchCategoryException
     * @throws InvalidArgumentException
     * @throws CouldNotDeleteException
     */
    public function delete($id): void
    {
        $id = Id::fromMixed($id);
        $model = $this->categoryRepository->getById($id);
        $this->categoryRepository->delete($model);
    }
}
