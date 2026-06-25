<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Event;

use Stringable;

/**
 * @psalm-type Reason = self::ENFORCED | self::DATA_CHANGED
 */
final class CacheInvalidated implements Stringable
{
    public const NAME = 'degree_program_cache_invalidated';
    public const ENFORCED = 'enforced';
    public const DATA_CHANGED = 'data_changed';

    /**
     * @param array<int> $ids
     * @param Reason $reason
     */
    private function __construct(
        private bool $isFully,
        private array $ids,
        private string $reason,
    ) {
    }

    /**
     * @param Reason $reason
     */
    public static function fully(string $reason): self
    {
        return new self(true, [], $reason);
    }

    /**
     * @param array<int> $ids
     * @param Reason $reason
     */
    public static function partially(array $ids, string $reason): self
    {
        return new self(false, $ids, $reason);
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

    /**
     * @return Reason
     */
    public function reason(): string
    {
        return $this->reason;
    }

    public function __toString(): string
    {
        return self::NAME;
    }
}
