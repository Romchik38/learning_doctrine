<?php

namespace App\Domain\Article;

use App\Domain\Category\Category;
use App\Infrastructure\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column]
    private bool $active;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    private Category|null $category = null;

    public function __construct()
    {
        $this->active = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function deactivate(): void
    {
        $this->active = false;
    }

    public function activate(): void
    {
        if($this->category === null) {
            throw new CannotActivateArticle('Assign a category to activate article');
        }
        $this->active = true;
    }

    public function changeCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function unsetCategory(): void
    {
        $this->category = null;
    }

    public function category(): null|Category {
        return $this->category;
    }
}
