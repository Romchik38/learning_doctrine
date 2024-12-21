<?php

declare(strict_types=1);

namespace App\Application\Article;

final class AddFromForm
{
    public const ID_FIELD = 'id';
    public const NAME_FIELD = 'name';
    public const SHORT_DESCRIPTION_FIELD = 'short_description';
    public const CHANGE_ACTIVITY_FIELD = 'change_activity';
    public const CATEGORY_SELECT_FIELD = 'category_select';
    public const CATEGORY_PLACEHOLDER = 'placeholder';
    public const CATEGORY_REMOVE_FIELD = 'category_remove';
    public const CATEGORY_REMOVE_ID_FIELD = 'category_remove_id';

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $shortDescription,
        public readonly string $changeActivity,
        public readonly string $categoryId,
        public readonly string $categoryRemoveValue,
        public readonly string $categoryRemoveId
    ) {}

    public static function fromHash(array $hash): self
    {
        return new self(
            $hash[self::ID_FIELD] ?? '',
            $hash[self::NAME_FIELD] ?? '',
            $hash[self::SHORT_DESCRIPTION_FIELD] ?? '',
            $hash[self::CHANGE_ACTIVITY_FIELD] ?? '',
            $hash[self::CATEGORY_SELECT_FIELD] ?? '',
            $hash[self::CATEGORY_REMOVE_FIELD] ?? '',
            $hash[self::CATEGORY_REMOVE_ID_FIELD] ?? '',
        );
    }
}
