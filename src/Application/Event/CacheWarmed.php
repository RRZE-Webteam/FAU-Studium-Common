<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Event;

use Stringable;

final class CacheWarmed implements Stringable
{
    public const NAME = 'degree_program_cache_warmed';

    private function __construct(
        private bool $isFully,
        private array $ids,
    ) {
    }

    public static function fully(): self
    {
        return new self(true, []);
    }

    public static function partially(array $ids): self
    {
        return new self(false, $ids);
    }

    public function isFully(): bool
    {
        return $this->isFully;
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
