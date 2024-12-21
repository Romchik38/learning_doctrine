<?php

declare(strict_types=1);

namespace App\Application\Category;

final class AddFromForm
{
    public const ID_FIELD = 'id';
    public const NAME_FIELD = 'name';

    public function __construct(
        public readonly string $id,
        public readonly string $name
    ) {}

    public static function fromHash(array $hash): self
    {
        return new self(
            $hash[self::ID_FIELD] ?? '',
            $hash[self::NAME_FIELD] ?? ''
        );
    }
}
