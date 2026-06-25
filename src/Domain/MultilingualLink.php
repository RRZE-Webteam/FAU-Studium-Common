<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

/**
 * @psalm-import-type MultilingualStringType from MultilingualString
 * @psalm-type MultilingualLink = array{
 *     id: string,
 *     name: MultilingualStringType,
 *     link_text: MultilingualStringType,
 *     link_url: MultilingualStringType
 * }
 * @psalm-type MultilingualLinkType = MultilingualLink & array{parent?: MultilingualLink|null}
 */
final class MultilingualLink
{
    public const ID = 'id';
    public const NAME = 'name';
    public const LINK_TEXT = 'link_text';
    public const LINK_URL = 'link_url';
    public const PARENT = 'parent';

    public const SCHEMA = [
        'type' => 'object',
        'additionalProperties' => true,
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
            MultilingualLink::PARENT,
        ],
        'properties' => [
            MultilingualLink::ID => [
                'type' => 'string',
                'minLength' => 1,
            ],
            MultilingualLink::NAME => MultilingualString::SCHEMA,
            MultilingualLink::LINK_TEXT => MultilingualString::SCHEMA,
            MultilingualLink::LINK_URL => MultilingualString::SCHEMA,
            MultilingualLink::PARENT => [
                'type' => ['object', 'null'],
                'additionalProperties' => true,
                'required' => [
                    MultilingualLink::ID,
                ],
                'properties' => [
                    MultilingualLink::ID => [
                        'type' => 'string',
                        'minLength' => 1,
                    ],
                ],
            ],
        ],
    ];

    private function __construct(
        private string $id,
        private MultilingualString $name,
        private MultilingualString $linkText,
        private MultilingualString $linkUrl,
        private ?MultilingualLink $parent,
    ) {
    }

    public static function new(
        string $id,
        MultilingualString $name,
        MultilingualString $linkText,
        MultilingualString $linkUrl,
        ?MultilingualLink $parent = null
    ): self {

        return new self($id, $name, $linkText, $linkUrl, $parent);
    }

    public static function empty(): self
    {
        return new self(
            '',
            MultilingualString::empty(),
            MultilingualString::empty(),
            MultilingualString::empty(),
            null
        );
    }

    /**
     * @psalm-param MultilingualLinkType $data
     */
    public static function fromArray(array $data): self
    {
        /** @var MultilingualLink|null $parentData */
        $parentData = $data[self::PARENT] ?? null;

        return new self(
            $data[self::ID],
            MultilingualString::fromArray($data[self::NAME]),
            MultilingualString::fromArray($data[self::LINK_TEXT]),
            MultilingualString::fromArray($data[self::LINK_URL]),
            !empty($parentData) ? self::fromArray($parentData) : null,
        );
    }

    /**
     * @return MultilingualLinkType
     */
    public function asArray(): array
    {
        /** @var MultilingualLink|null $parentData */
        $parentData = $this->parent?->asArray();

        return [
            self::ID => $this->id,
            self::NAME => $this->name->asArray(),
            self::LINK_TEXT => $this->linkText->asArray(),
            self::LINK_URL => $this->linkUrl->asArray(),
            self::PARENT => $parentData,
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

    public function parent(): ?MultilingualLink
    {
        return $this->parent;
    }
}
