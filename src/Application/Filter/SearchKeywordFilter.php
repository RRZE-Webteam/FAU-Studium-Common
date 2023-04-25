<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class SearchKeywordFilter implements Filter
{
    public const KEY = 'search';

    public function __construct(private string $keyword)
    {
    }

    public function value(): string
    {
        return $this->keyword;
    }

    public function id(): string
    {
        return self::KEY;
    }
}
