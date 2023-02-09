<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain\Event;

use Stringable;

final class DegreeProgramUpdated implements Stringable
{
    public const NAME = 'degree_program_updated';

    private function __construct(private int $id)
    {
    }

    public static function new(int $id): self
    {
        return new self($id);
    }

    public function __toString(): string
    {
        return self::NAME;
    }

    public function id(): int
    {
        return $this->id;
    }
}
