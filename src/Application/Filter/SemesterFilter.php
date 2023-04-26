<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class SemesterFilter implements Filter
{
    use ArrayOfIdsFilterTrait;

    public const KEY = 'semester';

    public function id(): string
    {
        return self::KEY;
    }
}
