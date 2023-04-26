<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class AttributeFilter implements Filter
{
    use ArrayOfIdsFilterTrait;

    public const KEY = 'attribute';

    public function id(): string
    {
        return self::KEY;
    }
}
