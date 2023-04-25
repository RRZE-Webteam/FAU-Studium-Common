<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class FacultyFilter implements Filter
{
    public const KEY = 'faculty';

    /**
     * @var array<int>
     */
    private array $faculties;

    public function __construct(
        int ...$faculty
    ) {

        $this->faculties = $faculty;
    }

    public function value(): array
    {
        return $this->faculties;
    }

    public function id(): string
    {
        return self::KEY;
    }
}
