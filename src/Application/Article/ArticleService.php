<?php

declare(strict_types=1);

namespace App\Application\Article;

use App\Application\Article\Views\ArticleViewDTO;
use App\Domain\Article\Article;
use App\Domain\Article\VO\ChangeActivity;
use App\Domain\Article\VO\Id;
use App\Domain\Article\VO\Name;
use App\Domain\Article\VO\ShortDescription;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

class ArticleService
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly ArticleRepository $articleRepository
    ) {}

    /**
     * @throws CantAddException
     * @throws NoSuchArticleException
     * @throws CantSaveException
     */
    public function add(AddFromForm $command): Id
    {
        $name = new Name($command->name);
        $shortDescription = new ShortDescription($command->shortDescription);
        $changeActivity = new ChangeActivity($command->changeActivity);

        if (strlen($command->id) > 0) {
            $model = $this->articleRepository->find($command->id);
        } else {
            $model = new Article();
        }

        $model->setName($name());
        $model->setShortDescription($shortDescription());

        if ($changeActivity() === true) {
            $current = $model->isActive();
            if (is_null($current)) {
                throw new CantSaveException(
                    sprintf('Cannot change activity of Article %s', $name)
                );
            }
            if ($current === true) {
                $model->deactivate();
            } else {
                $model->activate();
            }
        }

        $this->entityManager->persist($model);

        try {
            $this->entityManager->flush();
            return new Id($model->getId());
        } catch (\Exception $e) {
            throw new CantSaveException(
                sprintf('article with name %s was not saved', $name())
            );
        }
    }

    /**
     * @throws NoSuchArticleException
     */
    public function find($id): ArticleViewDTO
    {
        $id = Id::fromMixed($id);
        /** @var Article $model */
        $model = $this->articleRepository->find($id());
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
        $models = $this->articleRepository->findAll();
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
}
