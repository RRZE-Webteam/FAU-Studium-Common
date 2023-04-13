<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use ArrayObject;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use Fau\DegreeProgram\Common\Domain\MultilingualLinks;

/**
 * @template-extends ArrayObject<array-key, Link>
 * @psalm-import-type LinkType from Link
 */
final class Links extends ArrayObject
{
    private function __construct(Link ...$links)
    {
        parent::__construct($links);
    }

    public static function new(Link ...$links): self
    {
        return new self(...$links);
    }

    public static function fromMultilingualLinks(MultilingualLinks $multilingualLinks, string $languageCode): self
    {
        return new self(
            ...array_map(
                static fn(MultilingualLink $multilingualLink) => Link::fromMultilingualLink($multilingualLink, $languageCode),
                $multilingualLinks->getArrayCopy()
            )
        );
    }

    /**
     * @param array<LinkType> $items
     */
    public static function fromArray(array $items): self
    {
        return new self(
            ...array_map([Link::class, 'fromArray'], $items)
        );
    }

    /**
     * @return array<LinkType>
     */
    public function asArray(): array
    {
        return array_map(
            static fn(Link $link) => $link->asArray(),
            $this->getArrayCopy()
        );
    }

    public function asHtml(): string
    {
        return implode(', ', array_map(
            static fn(Link $link) => $link->asHtml(),
            $this->getArrayCopy()
        ));
    }
}
