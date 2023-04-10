<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

/**
 * @psalm-import-type MultilingualStringType from MultilingualString
 * @psalm-import-type MultilingualLinkType from MultilingualLink
 * @psalm-type AdmissionRequirement = array{
 *     id: string,
 *     name: MultilingualStringType,
 *     link_text: MultilingualStringType,
 *     link_url: MultilingualStringType,
 * }
 *
 * @psalm-type AdmissionRequirementType = AdmissionRequirement & array{
 *     parent: AdmissionRequirement|null
 * }
 */
final class AdmissionRequirement
{
    public const ID = 'id';
    public const NAME = 'name';
    public const LINK_TEXT = 'link_text';
    public const LINK_URL = 'link_url';

    public const PARENT = 'parent';

    public const SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [
            AdmissionRequirement::ID,
            AdmissionRequirement::NAME,
            AdmissionRequirement::LINK_TEXT,
            AdmissionRequirement::LINK_URL,
        ],
        'properties' => [
            AdmissionRequirement::ID => [
                'type' => 'string',
            ],
            AdmissionRequirement::NAME => MultilingualString::SCHEMA,
            AdmissionRequirement::LINK_TEXT => MultilingualString::SCHEMA,
            AdmissionRequirement::LINK_URL => MultilingualString::SCHEMA,
            AdmissionRequirement::PARENT => [
                'oneOf' => [
                    [
                        'type' => 'object',
                        'additionalProperties' => true,
                        'required' => [
                            AdmissionRequirement::ID,
                            AdmissionRequirement::NAME,
                        ],
                        'properties' => [
                            AdmissionRequirement::ID => [
                                'type' => 'string',
                            ],
                            AdmissionRequirement::NAME => MultilingualString::SCHEMA,
                        ],
                    ],
                    [
                        'type' => 'null',
                    ],
                ],
            ],
        ],
    ];

    private function __construct(
        private MultilingualLink $current,
        private ?AdmissionRequirement $parent,
    ) {
    }

    public static function new(
        MultilingualLink $current,
        ?AdmissionRequirement $parent,
    ): self {

        return new self($current, $parent);
    }

    public static function empty(): self
    {
        return new self(
            MultilingualLink::empty(),
            null
        );
    }

    /**
     * @psalm-param  AdmissionRequirementType $data
     */
    public static function fromArray(array $data): self
    {
        $currentData = [
            self::ID => $data[self::ID],
            self::NAME => $data[self::NAME],
            self::LINK_TEXT => $data[self::LINK_TEXT],
            self::LINK_URL => $data[self::LINK_URL],
        ];

        /** @var AdmissionRequirementType|null  $parentData */
        $parentData = $data[self::PARENT];

        return new self(
            MultilingualLink::fromArray($currentData),
            $parentData ? self::fromArray($parentData) : null
        );
    }

    /**
     * @return AdmissionRequirementType
     */
    public function asArray(): array
    {
        /** @var AdmissionRequirement|null $parentData */
        $parentData = $this->parent?->asArray();
        $currentData = $this->current->asArray();
        $currentData[self::PARENT] = $parentData;

        return $currentData;
    }

    public function id(): string
    {
        return $this->current->id();
    }

    public function name(): MultilingualString
    {
        return $this->current->name();
    }

    public function linkText(): MultilingualString
    {
        return $this->current->linkText();
    }

    public function linkUrl(): MultilingualString
    {
        return $this->current->linkUrl();
    }

    public function parent(): ?AdmissionRequirement
    {
        return $this->parent;
    }

    public function isEmpty(): bool
    {
        return !$this->current->id();
    }

    public function current(): MultilingualLink
    {
        return $this->current;
    }

    public function hasGermanName(string $name): bool
    {
        if ($this->name()->inGerman() === $name) {
            return true;
        }

        if (!$this->parent) {
            return false;
        }

        return $this->parent->name()->inGerman() === $name;
    }
}
