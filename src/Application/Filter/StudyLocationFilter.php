<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class StudyLocationFilter implements Filter
{
    use ArrayOfIdsFilterTrait;

    public const KEY = 'study-location';

    public function id(): string
    {
        return self::KEY;
    }
}
