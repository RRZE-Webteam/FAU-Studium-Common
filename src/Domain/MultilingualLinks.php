<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use ArrayObject;
use JsonSerializable;

/**
 * @template-extends ArrayObject<array-key, MultilingualLink>
 * @psalm-import-type MultilingualLinkType from MultilingualLink
 */
final class MultilingualLinks extends ArrayObject implements JsonSerializable
{
    public const SCHEMA = [
        'type' => 'array',
        'items' => MultilingualLink::SCHEMA,
    ];

    public const SCHEMA_REQUIRED = [
        'type' => 'array',
        'items' => MultilingualLink::SCHEMA_REQUIRED,
        'minItems' => 1,
    ];

    private function __construct(MultilingualLink ...$multilingualLinks)
    {
        parent::__construct($multilingualLinks);
    }

    public static function new(MultilingualLink ...$multilingualLinks): self
    {
        return new self(...$multilingualLinks);
    }

    /**
     * @param array<MultilingualLinkType> $items
     */
    public static function fromArray(array $items): self
    {
        return new self(
            ...array_map([MultilingualLink::class, 'fromArray'], $items)
        );
    }

    /**
     * @return array<MultilingualLinkType>
     */
    public function asArray(): array
    {
        return array_map(
            static fn(MultilingualLink $multilingualLink) => $multilingualLink->asArray(),
            $this->getArrayCopy()
        );
    }

    public function jsonSerialize()
    {
        return $this->asArray();
    }
}
