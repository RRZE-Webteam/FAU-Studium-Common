<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use ArrayObject;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;

/**
 * @template-extends  ArrayObject<array-key, MultilingualString>
 * @psalm-import-type MultilingualStringType from MultilingualString
 */
final class MultilingualList extends ArrayObject implements \JsonSerializable
{
    public const SCHEMA = [
        'type' => 'array',
        'items' => MultilingualString::SCHEMA,
    ];

    public const SCHEMA_REQUIRED = [
        'type' => 'array',
        'items' => MultilingualString::SCHEMA,
        'minItems' => 1,
    ];

    private function __construct(MultilingualString ...$strings)
    {
        parent::__construct($strings);
    }

    public static function new(MultilingualString ...$strings): self
    {
        return new self(...$strings);
    }

    /**
     * @param array<MultilingualStringType> $items
     */
    public static function fromArray(array $items): self
    {
        $multilingualStrings = [];
        foreach ($items as $item) {
            $multilingualStrings[] = MultilingualString::fromArray($item);
        }

        return new self(...$multilingualStrings);
    }

    /**
     * @return array<MultilingualStringType>
     */
    public function asArray(): array
    {
        return array_map(
            static fn(MultilingualString $multilingualString) => $multilingualString->asArray(),
            $this->getArrayCopy()
        );
    }

    public function asArrayOfStrings(string $languageCode): ArrayOfStrings
    {
        return ArrayOfStrings::new(
            ...array_map(
                static fn(MultilingualString $multilingualString): string => $multilingualString->asString($languageCode),
                $this->getArrayCopy()
            )
        );
    }

    public function jsonSerialize()
    {
        return $this->asArray();
    }

    public function containGermanString(string $string): bool
    {
        return in_array(
            $string,
            $this->asArrayOfStrings(MultilingualString::DE)->getArrayCopy(),
            true
        );
    }
}
