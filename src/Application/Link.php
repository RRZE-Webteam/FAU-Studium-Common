<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirement;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;

/**
 * @psalm-type LinkType = array{
 *     name: string,
 *     link_text: string,
 *     link_url: string,
 * }
 */
final class Link
{
    private function __construct(
        private string $name,
        private string $linkText,
        private string $linkUrl,
    ) {
    }

    public static function new(
        string $name,
        string $linkText,
        string $linkUrl,
    ): self {

        return new self($name, $linkText, $linkUrl);
    }

    public static function fromMultilingualLink(
        MultilingualLink|AdmissionRequirement $multilingualLink,
        string $languageCode
    ): self {

        return new self(
            $multilingualLink->name()->asString($languageCode),
            $multilingualLink->linkText()->asString($languageCode),
            $multilingualLink->linkUrl()->asString($languageCode),
        );
    }

    /**
     * @psalm-param LinkType $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data[MultilingualLink::NAME],
            $data[MultilingualLink::LINK_TEXT],
            $data[MultilingualLink::LINK_URL],
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

    /**
     * @return LinkType $data
     */
    public function asArray(): array
    {
        return [
            MultilingualLink::NAME => $this->name,
            MultilingualLink::LINK_TEXT => $this->linkText,
            MultilingualLink::LINK_URL => $this->linkUrl,
        ];
    }

    /**
     * @TODO: reconsider if this method is required
     */
    public function asHtml(): string
    {
        if ($this->linkText && $this->linkUrl) {
            return sprintf(
                '<a href="%s">%s</a>',
                $this->linkUrl,
                $this->linkText
            );
        }

        if ($this->linkText) {
            return $this->linkText;
        }

        return '';
    }
}
