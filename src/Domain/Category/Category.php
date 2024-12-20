<?php

declare(strict_types=1);

namespace App\Domain\Category;

use App\Domain\Category\VO\Name;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'category')]
final class Category
{

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::TEXT)]
    private string $name;

    public function __construct(Name $name)
    {
        $this->reName($name);
    }

    public function reName(Name $name): void
    {
        $this->name = $name();
    }
}
