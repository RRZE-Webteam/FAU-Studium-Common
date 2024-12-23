<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class SearchKeywordFilter implements Filter
{
    public const KEY = 'search';

    public function __construct(private string $keyword, private bool $extended = false)
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

    public static function fromInput(mixed $value): static
    {
        $sanitizedValue = self::sanitize($value);
        return $sanitizedValue ? new self($sanitizedValue) : self::empty();
    }

    public static function empty(): static
    {
        return new self('');
    }

    public function extended(): bool
    {
        return $this->extended;
    }

    private static function sanitize(mixed $value): ?string
    {
        return is_string($value) ? $value : null;
    }
}
