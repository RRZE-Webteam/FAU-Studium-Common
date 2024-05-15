<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use InvalidArgumentException;

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

    /** @var array<string, string> */
    private array $map = [];

    private function __construct()
    {
    }

    public static function empty(): self
    {
        return new self();
    }

    /**
     * @psalm-param array<string, string> $data
     */
    public static function fromArray(array $data): self
    {
        $instance = new self();

        foreach ($data as $key => $value) {
            $instance->set($key, $value);
        }

        return $instance;
    }

    public static function fromHisCode(string $hisCode): self
    {
        $parts = explode(self::HIS_CODE_DELIMITER, $hisCode);

        $instance = new self();

        if (isset($parts[0])) {
            $instance->set(DegreeProgram::DEGREE, $parts[0]);
        }

        if (isset($parts[1])) {
            $instance->set(DegreeProgram::AREA_OF_STUDY, $parts[1]);
        }

        if (isset($parts[6])) {
            $instance->set(DegreeProgram::LOCATION, $parts[6]);
        }

        return $instance;
    }

    public function set(string $key, string $value): self
    {
        if (! in_array($key, self::SUPPORTED_CAMPO_KEYS)) {
            throw new InvalidArgumentException('Unsupported field key.');
        }

        $this->map[$key] = $value;
        return $this;
    }

    public function degree(): ?string
    {
        return $this->get(DegreeProgram::DEGREE);
    }

    public function areaOfStudy(): ?string
    {
        return $this->get(DegreeProgram::AREA_OF_STUDY);
    }

    public function studyLocation(): ?string
    {
        return $this->get(DegreeProgram::LOCATION);
    }

    public function get(string $key): ?string
    {
        return $this->map[$key] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public function asArray(): array
    {
        return $this->map;
    }
}
