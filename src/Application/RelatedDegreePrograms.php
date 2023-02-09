<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use ArrayObject;
use JsonSerializable;

/**
 * @template-extends ArrayObject<array-key, RelatedDegreeProgram>
 * @psalm-import-type RelatedDegreeProgramType from RelatedDegreeProgram
 */
final class RelatedDegreePrograms extends ArrayObject implements JsonSerializable
{
    private function __construct(RelatedDegreeProgram ...$relatedDegreePrograms)
    {
        parent::__construct($relatedDegreePrograms);
    }

    public static function new(RelatedDegreeProgram ...$relatedDegreePrograms): self
    {
        return new self(...$relatedDegreePrograms);
    }

    /**
     * @psalm-param array<RelatedDegreeProgramType> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            ...array_map(
                [RelatedDegreeProgram::class, 'fromArray'],
                $data
            )
        );
    }

    /**
     * @return array<RelatedDegreeProgramType>
     */
    public function asArray(): array
    {
        return array_map(
            static fn(RelatedDegreeProgram $relatedDegreeProgram) => $relatedDegreeProgram->asArray(),
            $this->getArrayCopy()
        );
    }

    public function jsonSerialize()
    {
        return $this->asArray();
    }
}
