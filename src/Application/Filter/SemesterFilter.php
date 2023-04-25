<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class SemesterFilter implements Filter
{
    public const KEY = 'semester';

    /**
     * @var array<int>
     */
    private array $semesterIds;

    public function __construct(
        int ...$semesterId
    ) {

        $this->semesterIds = $semesterId;
    }

    public function value(): array
    {
        return $this->semesterIds;
    }

    public function id(): string
    {
        return self::KEY;
    }
}
