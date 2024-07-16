<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirement;

/**
 * @psalm-type AdmissionRequirementTranslated = array{
 *     name: string,
 *     link_text: string,
 *     link_url: string,
 *     slug: string
 * }
 * @psalm-type AdmissionRequirementTranslatedType = AdmissionRequirementTranslated
 *             & array{parent: AdmissionRequirementTranslated|null}
 */
final class AdmissionRequirementTranslated
{
    private function __construct(
        private Link $current,
        private ?AdmissionRequirementTranslated $parent,
        private string $slug
    ) {
    }

    public static function new(
        Link $current,
        ?AdmissionRequirementTranslated $parent,
        string $slug = ''
    ): self {

        return new self($current, $parent, $slug);
    }

    public static function fromAdmissionRequirement(
        AdmissionRequirement $admissionRequirement,
        string $languageCode
    ): self {

        return new self(
            Link::fromMultilingualLink($admissionRequirement->current(), $languageCode),
            $admissionRequirement->parent()
                ? self::fromAdmissionRequirement($admissionRequirement->parent(), $languageCode)
                : null,
            $admissionRequirement->slug()
        );
    }

    /**
     * @psalm-param AdmissionRequirementTranslatedType $data
     */
    public static function fromArray(array $data): self
    {
        $currentData = [
            AdmissionRequirement::NAME => $data[AdmissionRequirement::NAME],
            AdmissionRequirement::LINK_TEXT => $data[AdmissionRequirement::LINK_TEXT],
            AdmissionRequirement::LINK_URL => $data[AdmissionRequirement::LINK_URL],
        ];

        /** @var AdmissionRequirementTranslatedType|null  $parentData */
        $parentData = $data[AdmissionRequirement::PARENT];
        $slug = $data[AdmissionRequirement::SLUG] ?? '';

        return new self(
            Link::fromArray($currentData),
            $parentData ? self::fromArray($parentData) : null,
            $slug
        );
    }

    /**
     * @psalm-return AdmissionRequirementTranslatedType
     */
    public function asArray(): array
    {
        /** @var AdmissionRequirementTranslated|null $parentData */
        $parentData = $this->parent?->asArray();
        $currentData = $this->current->asArray();
        $currentData[AdmissionRequirement::PARENT] = $parentData;
        $currentData[AdmissionRequirement::SLUG] = $this->slug;

        return $currentData;
    }

    public function name(): string
    {
        return $this->current->name();
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function linkText(): string
    {
        return $this->current->linkText();
    }

    public function linkUrl(): string
    {
        return $this->current->linkUrl();
    }

    public function isEmpty(): bool
    {
        return !$this->linkUrl();
    }

    public function asHtml(): string
    {
        return $this->current->asHtml();
    }
}
