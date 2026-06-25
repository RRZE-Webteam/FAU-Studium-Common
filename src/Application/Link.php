<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirement;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;

/**
 * @psalm-type Link = array{
 *     name: string,
 *     link_text: string,
 *     link_url: string,
 * }
 * @psalm-type LinkType = Link & array{parent?: Link|null}
 */
final class Link
{
    private function __construct(
        private string $name,
        private string $linkText,
        private string $linkUrl,
        private ?Link $parent,
    ) {
    }

    public static function new(
        string $name,
        string $linkText,
        string $linkUrl,
        ?Link $parent = null
    ): self {

        return new self($name, $linkText, $linkUrl, $parent);
    }

    public static function empty(): self
    {

        return new self('', '', '', null);
    }

    public static function fromMultilingualLink(
        MultilingualLink|AdmissionRequirement $multilingualLink,
        string $languageCode
    ): self {

        return new self(
            $multilingualLink->name()->asString($languageCode),
            $multilingualLink->linkText()->asString($languageCode),
            $multilingualLink->linkUrl()->asString($languageCode),
            !is_null($multilingualLink->parent()) ? self::fromMultilingualLink($multilingualLink->parent(), $languageCode) : null,
        );
    }

    /**
     * @psalm-param LinkType $data
     */
    public static function fromArray(array $data): self
    {
        /** @var Link|null $parentData */
        $parentData = $data[MultilingualLink::PARENT] ?? null;

        return new self(
            $data[MultilingualLink::NAME],
            $data[MultilingualLink::LINK_TEXT],
            $data[MultilingualLink::LINK_URL],
            !empty($parentData) ? self::fromArray($parentData) : null,
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function linkText(): string
    {
        return $this->linkText;
    }

    public function linkUrl(): string
    {
        return $this->linkUrl;
    }

    public function parent(): ?Link
    {
        return $this->parent;
    }

    /**
     * @return LinkType $data
     */
    public function asArray(): array
    {
        /** @var Link|null $parentData */
        $parentData = $this->parent?->asArray();

        return [
            MultilingualLink::NAME => $this->name,
            MultilingualLink::LINK_TEXT => $this->linkText,
            MultilingualLink::LINK_URL => $this->linkUrl,
            MultilingualLink::PARENT => $parentData,
        ];
    }

    public function asHtml(): string
    {
        $linkText = $this->linkText ?: $this->name;

        if ($linkText && $this->linkUrl) {
            return sprintf(
                '<a href="%s">%s</a>',
                $this->linkUrl,
                $linkText
            );
        }

        if ($linkText) {
            return $linkText;
        }

        return '';
    }
}
