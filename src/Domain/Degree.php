<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use JsonSerializable;

/**
 * @psalm-import-type MultilingualStringType from MultilingualString
 * @psalm-type Degree = array{
 *     id: string,
 *     name: MultilingualStringType,
 *     abbreviation: MultilingualStringType,
 * }
 * @psalm-type DegreeType = Degree & array{parent: Degree|null}
 *
 * Psalm doesn't support self-referencing types, we need to provide workarounds.
 * @link https://github.com/vimeo/psalm/issues/5739
 */
final class Degree implements JsonSerializable
{
    public const ID = 'id';
    public const NAME = 'name';
    public const ABBREVIATION = 'abbreviation';
    public const PARENT = 'parent';

    public const SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [
            Degree::ID,
            Degree::NAME,
            Degree::ABBREVIATION,
            Degree::PARENT,
        ],
        'properties' => [
            Degree::ID => [
                'type' => 'string',
                'minLength' => 1,
            ],
            Degree::NAME => MultilingualString::SCHEMA,
            Degree::ABBREVIATION => MultilingualString::SCHEMA,
            Degree::PARENT => [
                'type' => 'object',
                'additionalProperties' => true,
                'required' => [Degree::ID],
                'properties' => [
                    Degree::ID => [
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
        private MultilingualString $abbreviation,
        private ?Degree $parent,
    ) {
    }

    public static function new(
        string $id,
        MultilingualString $name,
        MultilingualString $abbreviation,
        ?Degree $parent,
    ): self {

        return new self(
            $id,
            $name,
            $abbreviation,
            $parent
        );
    }

    public static function empty(): self
    {
        return new self(
            '',
            MultilingualString::empty(),
            MultilingualString::empty(),
            null,
        );
    }

    /**
     * @psalm-param DegreeType $data
     */
    public static function fromArray(array $data): self
    {
        /** @var DegreeType|null  $parentData */
        $parentData = $data[self::PARENT];
        return new self(
            $data[self::ID],
            MultilingualString::fromArray($data[self::NAME]),
            MultilingualString::fromArray($data[self::ABBREVIATION]),
            !empty($parentData) ? self::fromArray($parentData) : null,
        );
    }

    /**
     * @return DegreeType
     */
    public function asArray(): array
    {
        /** @var Degree|null $parentData */
        $parentData = $this->parent?->asArray();
        return [
            self::ID => $this->id,
            self::NAME => $this->name->asArray(),
            self::ABBREVIATION => $this->abbreviation->asArray(),
            self::PARENT => $parentData,
        ];
    }

    public function jsonSerialize()
    {
        return $this->asArray();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): MultilingualString
    {
        return $this->name;
    }

    public function abbreviation(): MultilingualString
    {
        return $this->abbreviation;
    }

    public function parent(): ?Degree
    {
        return $this->parent;
    }

    public function hasGermanAbbreviation(string $abbreviation): bool
    {
        if ($this->abbreviation->inGerman() === $abbreviation) {
            return true;
        }

        if (!$this->parent) {
            return false;
        }

        return $this->parent->abbreviation->inGerman() === $abbreviation;
    }
}
