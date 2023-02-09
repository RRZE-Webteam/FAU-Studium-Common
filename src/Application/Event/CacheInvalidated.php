<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Event;

use Stringable;

final class CacheInvalidated implements Stringable
{
    public const NAME = 'degree_program_cache_invalidated';

    private function __construct(
        private bool $isFull,
        private array $ids,
    ) {
    }

    public static function full(): self
    {
        return new self(true, []);
    }

    public static function partial(array $ids): self
    {
        return new self(false, $ids);
    }

    public function isFull(): bool
    {
        return $this->isFull;
    }

    public function ids(): array
    {
        return $this->ids;
    }

    public function __toString(): string
    {
        return self::NAME;
    }
}
