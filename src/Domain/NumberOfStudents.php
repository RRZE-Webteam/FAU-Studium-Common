<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

/**
 * @psalm-type NumberOfStudentsType = array{id: string, name: string, description: string}
 */
final class NumberOfStudents
{
    public const ID = 'id';
    public const NAME = 'name';
    public const DESCRIPTION = 'description';

    public const SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [
            NumberOfStudents::ID,
            NumberOfStudents::NAME,
            NumberOfStudents::DESCRIPTION,
        ],
        'properties' => [
            NumberOfStudents::ID => [
                'type' => 'string',
            ],
            NumberOfStudents::NAME => [
                'type' => 'string',
            ],
            NumberOfStudents::DESCRIPTION => [
                'type' => 'string',
            ],
        ],
    ];

    public const SCHEMA_REQUIRED =  [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [
            NumberOfStudents::ID,
            NumberOfStudents::NAME,
            NumberOfStudents::DESCRIPTION,
        ],
        'properties' => [
            NumberOfStudents::ID => [
                'type' => 'string',
                'minLength' => 1,
            ],
            NumberOfStudents::NAME => [
                'type' => 'string',
            ],
            NumberOfStudents::DESCRIPTION => [
                'type' => 'string',
            ],
        ],
    ];

    private function __construct(
        private string $id,
        private string $name,
        private string $description,
    ) {
    }

    public static function empty(): self
    {
        return new self('', '', '');
    }

    public static function new(string $id, string $name, string $description): self
    {
        return new self(
            $id,
            $name,
            $description,
        );
    }

    /**
     * @psalm-param NumberOfStudentsType $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data[self::ID],
            $data[self::NAME],
            $data[self::DESCRIPTION],
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return NumberOfStudentsType
     */
    public function asArray(): array
    {
        return [
            self::ID => $this->id,
            self::NAME => $this->name,
            self::DESCRIPTION => $this->description,
        ];
    }

    public function asString(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        return $this->asArray();
    }
}
