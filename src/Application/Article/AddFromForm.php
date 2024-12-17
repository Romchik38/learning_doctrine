<?php

declare(strict_types=1);

namespace App\Application\Article;

final class AddFromForm
{
    public const id_field = 'id';
    public const name_field = 'name';
    public const short_description_field = 'short_description';
    public const change_activity_field = 'change_activity';

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $shortDescription,
        public readonly string $changeActivity
    ) {}

    public static function fromHash(array $hash): self
    {
        return new self(
            $hash[self::id_field] ?? '',
            $hash[self::name_field] ?? '',
            $hash[self::short_description_field] ?? '',
            $hash[self::change_activity_field] ?? '',
        );
    }
}
