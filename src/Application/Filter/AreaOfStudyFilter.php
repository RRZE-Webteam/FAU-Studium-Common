<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class AreaOfStudyFilter implements Filter
{
    use ArrayOfIdsFilterTrait;

    public const KEY = 'area-of-study';

    public function id(): string
    {
        return self::KEY;
    }
}
