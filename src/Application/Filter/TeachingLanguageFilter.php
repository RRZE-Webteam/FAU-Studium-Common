<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class TeachingLanguageFilter implements Filter
{
    public const KEY = 'teaching-language';

    /**
     * @var array<int>
     */
    private array $languageIds;

    public function __construct(
        int ...$languageId
    ) {

        $this->languageIds = $languageId;
    }

    public function value(): array
    {
        return $this->languageIds;
    }

    public function id(): string
    {
        return self::KEY;
    }
}
