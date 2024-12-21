<?php

declare(strict_types=1);

namespace App\Application\Category;

final class AddFromForm
{
    public const id_field = 'id';
    public const name_field = 'name';

    public function __construct(
        public readonly string $id,
        public readonly string $name
    ) {}

    public static function fromHash(array $hash): self
    {
        return new self(
            $hash[self::id_field] ?? '',
            $hash[self::name_field] ?? ''
        );
    }
}
