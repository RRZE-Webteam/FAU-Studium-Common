<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

final class NumberOfStudents
{
    public const ID = 'id';
    public const DESCRIPTION = 'description';

    private function __construct(
        private string $id,
        private string $description,
    ) {
    }

    public static function empty(): self
    {
        return new self('', '');
    }

    public static function new(string $id, string $description): self
    {
        return new self(
            $id,
            $description,
        );
    }

    /**
     * @psalm-param array{id: string, description: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data[self::ID],
            $data[self::DESCRIPTION],
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return array{id: string, description: string}
     */
    public function asArray(): array
    {
        return [
            self::ID => $this->id,
            self::DESCRIPTION => $this->description,
        ];
    }

    public function asString(): string
    {
        return $this->description;
    }

    public function jsonSerialize(): array
    {
        return $this->asArray();
    }
}
