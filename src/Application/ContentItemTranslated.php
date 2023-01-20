<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\ContentItem;

/**
 * @psalm-type ContentItemTranslatedType = array{
 *     title: string,
 *     description: string,
 * }
 */
final class ContentItemTranslated
{
    private function __construct(
        private string $title,
        private string $description,
    ) {
    }

    public static function new(
        string $title,
        string $description,
    ): self {

        return new self(
            $title,
            $description,
        );
    }

    public static function fromContentItem(
        ContentItem $contentItem,
        string $languageCode
    ): self {

        return new self(
            $contentItem->title()->asString($languageCode),
            $contentItem->description()->asString($languageCode),
        );
    }

    /**
     * @return ContentItemTranslatedType
     */
    public function asArray(): array
    {
        return [
            ContentItem::TITLE => $this->title,
            ContentItem::DESCRIPTION => $this->description,
        ];
    }

    public function asString(): string
    {
        return sprintf(
            '%s: %s',
            $this->title,
            $this->description
        );
    }
}
