<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class TeachingLanguageFilter implements Filter
{
    use ArrayOfIdsFilterTrait;

    public const KEY = 'teaching-language';

    public function id(): string
    {
        return self::KEY;
    }
}
