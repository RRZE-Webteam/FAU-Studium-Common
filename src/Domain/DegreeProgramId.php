<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use InvalidArgumentException;
use JsonSerializable;

final class DegreeProgramId implements JsonSerializable
{
    private function __construct(
        private int $id,
    ) {

        $id >= 0 or throw new InvalidArgumentException();
    }

    public static function fromInt(int $id): self
    {
        return new self($id);
    }

    public function asInt(): int
    {
        return $this->id;
    }

    public function jsonSerialize(): int
    {
        return $this->asInt();
    }
}
