<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

/**
 * @psalm-type CampoKeysMap = array<value-of<self::SUPPORTED_CAMPO_KEYS>, array<int, string>>
 */
final class CampoKeys
{
    public const SCHEMA = [
        'type' => 'object',
        'properties' => [
            DegreeProgram::DEGREE => [
                'type' => 'array',
            ],
            DegreeProgram::AREA_OF_STUDY => [
                'type' => 'array',
            ],
            DegreeProgram::LOCATION => [
                'type' => 'array',
            ],
        ],
    ];

    public const SCHEMA_REQUIRED = [
        'type' => 'object',
        'properties' => [
            DegreeProgram::DEGREE => [
                'type' => 'array',
            ],
            DegreeProgram::AREA_OF_STUDY => [
                'type' => 'array',
            ],
            DegreeProgram::LOCATION => [
                'type' => 'array',
            ],
        ],
    ];

    public const SUPPORTED_CAMPO_KEYS = [
        DegreeProgram::DEGREE,
        DegreeProgram::AREA_OF_STUDY,
        DegreeProgram::LOCATION,
    ];

    private function __construct(
        /**
         * @var CampoKeysMap $map
         */
        private array $map
    ) {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @param CampoKeysMap $map
     */
    public static function fromArray(array $map): self
    {
        return new self($map);
    }

    /**
     * @return CampoKeysMap
     */
    public function asArray(): array
    {
        return $this->map;
    }
}
