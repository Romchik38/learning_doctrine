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
use InvalidArgumentException;

class ArticleService
{
    public function __construct(
        protected readonly ArticleRepositoryInterface $articleRepository
    ) {}

    /**
     * @throws NoSuchArticleException
     * @throws CouldNotSaveException
     * @throws InvalidArgumentException
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
