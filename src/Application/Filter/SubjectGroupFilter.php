<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class SubjectGroupFilter implements Filter
{
    public const KEY = 'subject-group';

    /**
     * @var array<int>
     */
    private array $subjectGroups;

    public function __construct(
        int ...$subjectGroup
    ) {

        $this->subjectGroups = $subjectGroup;
    }

    public function value(): array
    {
        return $this->subjectGroups;
    }

    public function id(): string
    {
        return self::KEY;
    }
}
