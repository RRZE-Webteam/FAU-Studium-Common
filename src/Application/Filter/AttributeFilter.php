<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class AttributeFilter implements Filter
{
    public const KEY = 'attribute';

    /**
     * @var array<int>
     */
    private array $attributes;

    public function __construct(
        int ...$attribute
    ) {

        $this->attributes = $attribute;
    }

    public function value(): array
    {
        return $this->attributes;
    }

    public function id(): string
    {
        return self::KEY;
    }
}
