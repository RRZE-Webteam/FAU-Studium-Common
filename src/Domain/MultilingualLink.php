<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

/**
 * @psalm-import-type MultilingualStringType from MultilingualString
 * @psalm-type MultilingualLinkType = array{
 *     id: string,
 *     name: MultilingualStringType,
 *     link_text: MultilingualStringType,
 *     link_url: MultilingualStringType
 * }
 */
final class MultilingualLink
{
    public const ID = 'id';
    public const NAME = 'name';
    public const LINK_TEXT = 'link_text';
    public const LINK_URL = 'link_url';

    public const SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [
            MultilingualLink::ID,
            MultilingualLink::NAME,
            MultilingualLink::LINK_TEXT,
            MultilingualLink::LINK_URL,
        ],
        'properties' => [
            MultilingualLink::ID => [
                'type' => 'string',
            ],
            MultilingualLink::NAME => MultilingualString::SCHEMA,
            MultilingualLink::LINK_TEXT => MultilingualString::SCHEMA,
            MultilingualLink::LINK_URL => MultilingualString::SCHEMA,
        ],
    ];

    public const SCHEMA_REQUIRED = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [
            MultilingualLink::ID,
            MultilingualLink::NAME,
            MultilingualLink::LINK_TEXT,
            MultilingualLink::LINK_URL,
        ],
        'properties' => [
            MultilingualLink::ID => [
                'type' => 'string',
                'minLength' => 1,
            ],
            MultilingualLink::NAME => MultilingualString::SCHEMA,
            MultilingualLink::LINK_TEXT => MultilingualString::SCHEMA,
            MultilingualLink::LINK_URL => MultilingualString::SCHEMA,
        ],
    ];

    private function __construct(
        private string $id,
        private MultilingualString $name,
        private MultilingualString $linkText,
        private MultilingualString $linkUrl,
    ) {
    }

    public static function new(
        string $id,
        MultilingualString $name,
        MultilingualString $linkText,
        MultilingualString $linkUrl,
    ): self {

        return new self($id, $name, $linkText, $linkUrl);
    }

    public static function empty(): self
    {
        return new self(
            '',
            MultilingualString::empty(),
            MultilingualString::empty(),
            MultilingualString::empty(),
        );
    }

    /**
     * @psalm-param MultilingualLinkType $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data[self::ID],
            MultilingualString::fromArray($data[self::NAME]),
            MultilingualString::fromArray($data[self::LINK_TEXT]),
            MultilingualString::fromArray($data[self::LINK_URL]),
        );
    }

    /**
     * @return MultilingualLinkType
     */
    public function asArray(): array
    {
        return [
            self::ID => $this->id,
            self::NAME => $this->name->asArray(),
            self::LINK_TEXT => $this->linkText->asArray(),
            self::LINK_URL => $this->linkUrl->asArray(),
        ];
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): MultilingualString
    {
        return $this->name;
    }

    public function linkText(): MultilingualString
    {
        return $this->linkText;
    }

    public function linkUrl(): MultilingualString
    {
        return $this->linkUrl;
    }
}
