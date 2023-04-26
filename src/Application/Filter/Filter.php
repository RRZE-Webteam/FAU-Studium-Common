<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

interface Filter
{
    public function id(): string;
    public function value(): mixed;
    public static function fromInput(mixed $value): static;
    public static function empty(): static;
}
