<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Event;

use Stringable;

final class CacheInvalidated implements Stringable
{
    public const NAME = 'degree_program_cache_invalidated';

    /**
     * @param array<int> $ids
     */
    private function __construct(
        private bool $isFully,
        private array $ids,
    ) {
    }

    public static function fully(): self
    {
        return new self(true, []);
    }

    /**
     * @param array<int> $ids
     */
    public static function partially(array $ids): self
    {
        return new self(false, $ids);
    }

    public function isFully(): bool
    {
        return $this->isFully;
    }

    /**
     * @return array<int>
     */
    public function ids(): array
    {
        return $this->ids;
    }

    public function __toString(): string
    {
        return self::NAME;
    }
}
