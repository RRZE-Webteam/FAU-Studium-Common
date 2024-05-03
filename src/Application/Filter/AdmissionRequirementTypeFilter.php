<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

/**
 * @psalm-type AdmissionRequirementType = 'frei' | 'eingeschraenkt'
 */
final class AdmissionRequirementTypeFilter implements Filter
{
    public const FREE = 'frei';
    public const FREE_WITH_RESTRICTION = 'frei-mit-einschrankung';
    public const RESTRICTED = 'eingeschraenkt';

    public const KEY = 'admission-requirement';

    /**
     * @var array<AdmissionRequirementType>
     */
    private array $types;

    /**
     * @psalm-param AdmissionRequirementType $types
     */
    private function __construct(
        string ...$types
    ) {

        $this->types = $types;
    }

    public function id(): string
    {
        return self::KEY;
    }

    /**
     * @psalm-return array<AdmissionRequirementType>
     */
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
     * @psalm-return ?array<AdmissionRequirementType>
     */
    private static function sanitize(mixed $value): ?array
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }

        if (!is_array($value)) {
            return null;
        }

        /** @psalm-var array<AdmissionRequirementType> $value */
        $value = array_filter(
            $value,
            static fn ($item) => in_array($item, [self::FREE, self::FREE_WITH_RESTRICTION, self::RESTRICTED], true),
        );

        return $value;
    }
}
