<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\Degree;

/**
 * @psalm-type DegreeTranslated = array{
 *     name: string,
 *     abbreviation: string,
 * }
 * @psalm-type DegreeTranslatedType = DegreeTranslated & array{parent: DegreeTranslated|null}
 *
 * Psalm doesn't support self-referencing types, we need to provide workarounds.
 * @link https://github.com/vimeo/psalm/issues/5739
 */
final class DegreeTranslated
{
    private function __construct(
        private string $name,
        private string $abbreviation,
        private ?DegreeTranslated $parent,
    ) {
    }

    public static function new(
        string $name,
        string $abbreviation,
        ?DegreeTranslated $parent
    ): self {

        return new self(
            $name,
            $abbreviation,
            $parent,
        );
    }

    public static function fromDegree(Degree $degree, string $languageCode): self
    {
        return new self(
            $degree->name()->asString($languageCode),
            $degree->abbreviation()->asString($languageCode),
            $degree->parent() ? self::fromDegree($degree->parent(), $languageCode) : null,
        );
    }

    /**
     * @psalm-param DegreeTranslatedType $data
     */
    public static function fromArray(array $data): self
    {
        /** @var DegreeTranslatedType|null  $parentData */
        $parentData = $data[Degree::PARENT];
        return new self(
            $data[Degree::NAME],
            $data[Degree::ABBREVIATION],
            !empty($parentData) ? self::fromArray($parentData) : null,
        );
    }

    /**
     * @return DegreeTranslatedType $data
     */
    public function asArray(): array
    {
        /** @var DegreeTranslated|null $parentData */
        $parentData = $this->parent?->asArray();
        return [
            Degree::NAME => $this->name,
            Degree::ABBREVIATION => $this->abbreviation,
            Degree::PARENT => $parentData,
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

    public function parent(): ?DegreeTranslated
    {
        return $this->parent;
    }
}
