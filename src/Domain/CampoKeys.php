<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

/**
 * @psalm-type CampoKeysMap = array<value-of<self::SUPPORTED_CAMPO_KEYS>, string>
 */
final class CampoKeys
{
    public const SCHEMA = [
        'type' => 'object',
        'properties' => [
            DegreeProgram::DEGREE => [
                'type' => 'string',
            ],
            DegreeProgram::AREA_OF_STUDY => [
                'type' => 'string',
            ],
            DegreeProgram::LOCATION => [
                'type' => 'string',
            ],
        ],
    ];

    public const SCHEMA_REQUIRED = [
        'type' => 'object',
        'properties' => [
            DegreeProgram::DEGREE => [
                'type' => 'string',
            ],
            DegreeProgram::AREA_OF_STUDY => [
                'type' => 'string',
            ],
            DegreeProgram::LOCATION => [
                'type' => 'string',
            ],
        ],
    ];

    public const SUPPORTED_CAMPO_KEYS = [
        DegreeProgram::DEGREE,
        DegreeProgram::AREA_OF_STUDY,
        DegreeProgram::LOCATION,
    ];

    private const HIS_CODE_DELIMITER = '|';

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

    public static function fromHisCode(string $hisCode): self
    {
        $parts = explode(self::HIS_CODE_DELIMITER, $hisCode);
        $map = [];


        if (isset($parts[0])) {
            $map[DegreeProgram::DEGREE] = $parts[0];
        }

        if (isset($parts[1])) {
            $map[DegreeProgram::AREA_OF_STUDY] = $parts[1];
        }

        if (isset($parts[6])) {
            $map[DegreeProgram::LOCATION] = $parts[6];
        }

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
