<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class SubjectGroupFilter implements Filter
{
    use ArrayOfIdsFilterTrait;

    public const KEY = 'subject-group';

    public function id(): string
    {
        return self::KEY;
    }
}
