<?php

declare(strict_types=1);

namespace App\Domain\Category;

use App\Domain\Category\VO\Id;

interface CategoryRepositoryInterface
{
    /** @throws CouldNotDeleteException */
    public function delete(Category $model): void;

    /** @return array<int,Category> */
    public function getAll(): array;

    /** @throws NoSuchCategoryException */
    public function getById(Id $id): Category;

    /** @throws CouldNotSaveException */
    public function save(Category $model): void;
}
