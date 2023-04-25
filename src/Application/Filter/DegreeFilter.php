<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class DegreeFilter implements Filter
{
    public const KEY = 'degree';

    /**
     * @var array<int>
     */
    private array $degreeIds;

    public function __construct(
        int ...$degreeId
    ) {

        $this->degreeIds = $degreeId;
    }

    public function value(): array
    {
        return $this->degreeIds;
    }

    public function id(): string
    {
        return self::KEY;
    }
}
