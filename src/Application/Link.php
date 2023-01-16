<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\MultilingualLink;

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

    public static function fromMultilingualLink(MultilingualLink $multilingualLink, string $languageCode): self
    {
        return new self(
            $multilingualLink->name()->asString($languageCode),
            $multilingualLink->linkText()->asString($languageCode),
            $multilingualLink->linkUrl()->asString($languageCode),
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
