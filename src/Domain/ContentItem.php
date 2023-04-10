<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

/**
 * @psalm-import-type MultilingualStringType from MultilingualString
 * @psalm-type ContentItemType = array{
 *     title: MultilingualStringType,
 *     description: MultilingualStringType
 * }
 */
final class ContentItem
{
    public const TITLE = 'title';
    public const DESCRIPTION = 'description';

    public const SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [ContentItem::TITLE, ContentItem::DESCRIPTION],
        'properties' => [
            ContentItem::TITLE => MultilingualString::SCHEMA,
            ContentItem::DESCRIPTION => MultilingualString::SCHEMA,
        ],
    ];

    public const SCHEMA_REQUIRED = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [ContentItem::TITLE, ContentItem::DESCRIPTION],
        'properties' => [
            ContentItem::TITLE => MultilingualString::SCHEMA,
            ContentItem::DESCRIPTION => MultilingualString::SCHEMA_REQUIRED,
        ],
    ];

    private function __construct(
        private MultilingualString $title,
        private MultilingualString $description,
        ?MultilingualString $defaultTitle,
    ) {

        if ($defaultTitle instanceof MultilingualString) {
            $this->title = $this->title->mergeWithDefault($defaultTitle);
        }
    }

    public static function new(
        MultilingualString $title,
        MultilingualString $description,
    ): self {

        return new self(
            $title,
            $description,
            null,
        );
    }

    /**
     * @psalm-param ContentItemType $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            MultilingualString::fromArray($data[self::TITLE]),
            MultilingualString::fromArray($data[self::DESCRIPTION]),
            null,
        );
    }

    /**
     * @return ContentItemType
     */
    public function asArray(): array
    {
        return [
            self::TITLE => $this->title->asArray(),
            self::DESCRIPTION => $this->description->asArray(),
        ];
    }

    public function withDefaultTitle(MultilingualString $defaultTitle): self
    {
        return new self(
            $this->title,
            $this->description,
            $defaultTitle
        );
    }

    public function title(): MultilingualString
    {
        return $this->title;
    }

    public function description(): MultilingualString
    {
        return $this->description;
    }
}
