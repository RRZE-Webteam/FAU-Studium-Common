<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\Degree;

/**
 * @psalm-type DegreeTranslatedType = array{
 *     name: string,
 *     abbreviation: string,
 * }
 */
final class DegreeTranslated
{
    private function __construct(
        private string $name,
        private string $abbreviation,
    ) {
    }

    public static function new(
        string $name,
        string $abbreviation,
    ): self {

        return new self(
            $name,
            $abbreviation
        );
    }

    public static function fromDegree(Degree $degree, string $languageCode): self
    {
        return new self(
            $degree->name()->asString($languageCode),
            $degree->abbreviation()->asString($languageCode),
        );
    }

    /**
     * @psalm-param DegreeTranslatedType $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data[Degree::NAME],
            $data[Degree::ABBREVIATION],
        );
    }

    /**
     * @return DegreeTranslatedType $data
     */
    public function asArray(): array
    {
        return [
            Degree::NAME => $this->name,
            Degree::ABBREVIATION => $this->abbreviation,
        ];
    }

    public function name(): string
    {
        return $this->name;
    }

    public function abbreviation(): string
    {
        return $this->abbreviation;
    }
}
