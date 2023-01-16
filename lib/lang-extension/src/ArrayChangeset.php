<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\LanguageExtension;

/**
 * @template T
 */
class ArrayChangeset
{
    /**
     * @param array<T> $old
     * @param array<T> $new
     */
    private function __construct(
        private array $old,
        private array $new,
    ){}

    /**
     * @template T
     * @param array<T> $data
     */
    public static function new(
        array $data,
    ): static {
        return new static(
            $data,
            $data,
        );
    }

    /**
     * @param array<T> $new
     */
    public function applyChanges(array $new): static
    {
        return new static(
            $this->old,
            $new
        );
    }

    /**
     * @return array<T>
     */
    public function added(): array
    {
        return array_diff($this->new, $this->old);
    }

    /**
     * @return array<T>
     */
    public function removed(): array
    {
        return array_diff($this->old, $this->new);
    }
}
