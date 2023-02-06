<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use JsonSerializable;
use Webmozart\Assert\Assert;

final class DegreeProgramId implements JsonSerializable
{
    private function __construct(
        private int $id,
    ) {

        Assert::positiveInteger($this->id);
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
