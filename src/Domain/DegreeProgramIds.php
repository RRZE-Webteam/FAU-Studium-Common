<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use ArrayObject;
use JsonSerializable;

/**
 * @template-extends  ArrayObject<array-key, DegreeProgramId>
 */
final class DegreeProgramIds extends ArrayObject implements JsonSerializable
{
    private function __construct(DegreeProgramId ...$ids)
    {
        parent::__construct($ids);
    }

    public static function new(DegreeProgramId ...$ids): self
    {
        return new self(...$ids);
    }

    /**
     * @param array<int> $ids
     */
    public static function fromArray(array $ids): self
    {
        return new self(
            ...array_map([DegreeProgramId::class, 'fromInt'], $ids)
        );
    }

    /**
     * @return array<int>
     */
    public function asArray(): array
    {
        return array_map(
            static fn(DegreeProgramId $degreeProgramId) => $degreeProgramId->asInt(),
            $this->getArrayCopy()
        );
    }

    public function jsonSerialize(): array
    {
        return $this->asArray();
    }
}
