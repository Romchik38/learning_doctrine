<?php

declare(strict_types=1);

namespace App\Domain\Category;

use App\Domain\Article\Article;
use App\Domain\Category\VO\Id;
use App\Domain\Category\VO\Name;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'category')]
class Category
{

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::TEXT)]
    private string $name;

    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'category')]
    private Collection $articles;

    public function __construct(Name $name)
    {
        $this->reName($name);
        $this->articles = new ArrayCollection();
    }

    public function id(): Id {
        return new Id($this->id);
    }

    public function name(): Name {
        return new Name($this->name);
    }

    public function reName(Name $name): void
    {
        $this->name = $name();
    }

     /**
     * @return Collection<int, Article>
     */
    public function articles(): Collection {
        return $this->articles();
    }
}
