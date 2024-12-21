<?php

declare(strict_types=1);

namespace App\Application\Article;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\Article\CouldNotDeleteException;
use App\Domain\Article\VO\ChangeActivity;
use App\Domain\Article\VO\Id;
use App\Domain\Article\VO\Name;
use App\Domain\Article\VO\ShortDescription;
use App\Domain\Article\CouldNotSaveException;
use App\Domain\Article\VO\RemoveCategory;
use App\Domain\Category\CategoryRepositoryInterface;
use App\Domain\Category\NoSuchCategoryException;
use App\Domain\Category\VO\Id as CategoryId;
use InvalidArgumentException;

final class ArticleService
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * @throws NoSuchArticleException
     * @throws CouldNotSaveException
     * @throws InvalidArgumentException
     * @throws NoSuchCategoryException
     */
    public function save(AddFromForm $command): Id
    {
        $name = new Name($command->name);
        $shortDescription = new ShortDescription($command->shortDescription);
        $changeActivity = new ChangeActivity($command->changeActivity);

        if (strlen($command->id) > 0) {
            $id = Id::fromString($command->id);
            $model = $this->articleRepository->getById($id);
        } else {
            $model = new Article();
        }

        $model->setName($name());
        $model->setShortDescription($shortDescription());

        /** activity */
        if ($changeActivity() === true) {
            $current = $model->isActive();
            if (is_null($current)) {
                throw new CouldNotSaveException(
                    sprintf('Cannot change activity of Article %s', $name)
                );
            }
            if ($current === true) {
                $model->deactivate();
            } else {
                $model->activate();
            }
        }

        /** category */
        if (
            $command::CATEGORY_PLACEHOLDER !== $command->categoryId
            && strlen($command->categoryId) > 0
        ) {
            $categoryId = CategoryId::fromString($command->categoryId);
            $categoryModel = $this->categoryRepository->getById($categoryId);
            $model->changeCategory($categoryModel);
        } elseif (strlen($command->categoryRemoveValue) > 0) {
            $removeCategory = new RemoveCategory($command->categoryRemoveValue);
            if($removeCategory->remove === true) {
                $model->unsetCategory();
            }
        }

        /** save */
        $this->articleRepository->save($model);
        return new Id($model->getId());
    }

    /**
     * @throws NoSuchArticleException
     * @throws InvalidArgumentException
     * @throws CouldNotDeleteException
     */
    public function delete($id): void
    {
        $id = Id::fromMixed($id);
        $model = $this->articleRepository->getById($id);
        $this->articleRepository->delete($model);
    }
}
