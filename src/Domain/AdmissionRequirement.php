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
}
