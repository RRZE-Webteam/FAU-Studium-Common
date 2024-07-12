<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class AdmissionRequirementTypeFilter implements Filter
{
    public const FREE = 'frei';
    public const RESTRICTED = 'eingeschraenkt';

    public const KEY = 'admission-requirement';

    /**
     * @var array<string>
     */
    private array $types;

    private function __construct(
        string ...$types
    ) {

        $this->types = $types;
    }

    public function id(): string
    {
        return self::KEY;
    }

    public function value(): array
    {
        return array_unique($this->types);
    }

    public static function fromInput(mixed $value): static
    {
        $sanitizedValue = self::sanitize($value);

        return $sanitizedValue ? new self(...$sanitizedValue) : self::empty();
    }

    public static function empty(): static
    {
        return new self();
    }

    /**
     * @return array<string>|null
     */
    private static function sanitize(mixed $value): ?array
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }

        if (!is_array($value)) {
            return null;
        }

        return array_filter(
            $value,
            'is_string',
        );
    }
}
