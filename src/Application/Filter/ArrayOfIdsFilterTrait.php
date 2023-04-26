<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

trait ArrayOfIdsFilterTrait
{
    /**
     * @var array<int>
     */
    private array $ids;

    private function __construct(
        int ...$ids
    ) {

        $this->ids = $ids;
    }

    public function value(): array
    {
        return $this->ids;
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
     * @return array<int>|null
     */
    private static function sanitize(mixed $value): ?array
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }

        if (!is_array($value)) {
            return null;
        }

        $sanitizedArray = array_filter(array_map('intval', $value));
        return $sanitizedArray;
    }
}
