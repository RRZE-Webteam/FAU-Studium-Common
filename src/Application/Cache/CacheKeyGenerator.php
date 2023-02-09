<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Cache;

use Exception;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Psr\SimpleCache\InvalidArgumentException;

final class CacheKeyGenerator
{
    public const RAW_TYPE = 'raw';
    public const TRANSLATED_TYPE = 'translated';
    /**
     * @psalm-param 'raw' | 'translated' $type
     */
    public function generateForDegreeProgram(DegreeProgramId $degreeProgramId, string $type = self::RAW_TYPE): string
    {
        return $this->generate($type, (string) $degreeProgramId->asInt());
    }

    /**
     * @psalm-return list{'raw' | 'translated', int}
     */
    public function parseForDegreeProgram(string $key): array
    {
        $parts = explode('_', $key);

        if (!$this->isValidKeyParts($parts)) {
            throw new class (
                "Cache {$key} is invalid."
            ) extends Exception implements InvalidArgumentException {
            };
        }

        /** @var 'raw' | 'translated' $type */
        $type = $parts[2];

        return [$type, (int) $parts[3]];
    }

    private function generate(string ...$parts): string
    {
        array_unshift($parts, 'fau', 'cache');

        return implode('_', $parts);
    }

    private function isValidKeyParts(array $parts): bool
    {
        if (count($parts) !== 4) {
            return false;
        }

        if ($parts[0] !== 'fau' || $parts[1] !== 'cache') {
            return false;
        }

        if (!in_array($parts[2], [self::RAW_TYPE, self::TRANSLATED_TYPE], true)) {
            return false;
        }

        return (int) $parts[3] >= 0;
    }
}
