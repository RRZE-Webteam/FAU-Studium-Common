<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class AreaOfStudyFilter implements Filter
{
    public const KEY = 'area-of-study';

    /**
     * @var array<int>
     */
    private array $locationIds;

    public function __construct(
        int ...$locationId
    ) {

        $this->locationIds = $locationId;
    }

    public function value(): array
    {
        return $this->locationIds;
    }

    public function id(): string
    {
        return self::KEY;
    }
}
