<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use JsonSerializable;

/**
 * @psalm-import-type MultilingualStringType from MultilingualString
 */
final class Degree implements JsonSerializable
{
    public const ID = 'id';
    public const NAME = 'name';
    public const ABBREVIATION = 'abbreviation';

    private function __construct(
        private string $id,
        private MultilingualString $name,
        private MultilingualString $abbreviation,
    ) {
    }

    public static function new(
        string $id,
        MultilingualString $name,
        MultilingualString $abbreviation,
    ): self {

        return new self(
            $id,
            $name,
            $abbreviation
        );
    }

    public static function empty(): self
    {
        return new self(
            '',
            MultilingualString::empty(),
            MultilingualString::empty(),
        );
    }

    /**
     * @psalm-param array{
     *     id: string,
     *     name: MultilingualStringType,
     *     abbreviation: MultilingualStringType
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data[self::ID],
            MultilingualString::fromArray($data[self::NAME]),
            MultilingualString::fromArray($data[self::ABBREVIATION]),
        );
    }

    public function asString(string $languageCode): string
    {
        return sprintf(
            '%s: %s',
            $this->abbreviation->asString($languageCode),
            $this->name->asString($languageCode)
        );
    }

    /**
     * @return array{
     *     id: string,
     *     name: MultilingualStringType,
     *     abbreviation: MultilingualStringType
     * }
     */
    public function asArray(): array
    {
        return [
            self::ID => $this->id,
            self::NAME => $this->name->asArray(),
            self::ABBREVIATION => $this->abbreviation->asArray(),
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
}
