<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use ArrayObject;
use BadMethodCallException;
use JsonSerializable;

/**
 * @template-extends ArrayObject<string, Violation>
 * @psalm-import-type ViolationType from Violation
 */
final class Violations extends ArrayObject implements JsonSerializable
{
    private function __construct(Violation ...$violations)
    {
        $result = [];

        foreach ($violations as $violation) {
            $result[$violation->path()] = $violation;
        }

        parent::__construct($result);
    }

    public static function new(Violation ...$violations): self
    {
        return new self(...$violations);
    }

    /**
     * Undocumented function
     *
     * @return array<string, ViolationType>
     */
    public function asArray(): array
    {
        return array_map(
            static fn(Violation $violation) => $violation->asArray(),
            $this->getArrayCopy()
        );
    }

    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }

    public function append(mixed $value): void
    {
        throw new BadMethodCallException("Invalid append call");
    }

    public function add(Violation ...$violations): void
    {
        foreach ($violations as $violation) {
            $this->offsetSet($violation->path(), $violation);
        }
    }
}
