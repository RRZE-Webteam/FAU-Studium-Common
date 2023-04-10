<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use InvalidArgumentException;
use JsonSerializable;

final class Image implements JsonSerializable
{
    public const ID = 'id';
    public const URL = 'url';

    public const SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [Image::ID, Image::URL],
        'properties' => [
            Image::ID => [
                'type' => 'integer',
                'minimum' => 0,
            ],
            Image::URL => [
                'type' => 'string',
            ],
        ],
    ];

    public const SCHEMA_REQUIRED = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [Image::ID, Image::URL],
        'properties' => [
            Image::ID => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            Image::URL => [
                'type' => 'string',
            ],
        ],
    ];

    private function __construct(
        private int $id,
        private string $url,
    ) {

        $id >= 0 or throw new InvalidArgumentException();
    }

    public static function empty(): self
    {
        return new self(0, '');
    }

    public static function new(int $id, string $url): self
    {
        return new self(
            $id,
            $url,
        );
    }

    /**
     * @psalm-param array{id: int, url: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data[self::ID],
            $data[self::URL],
        );
    }

    /**
     * @return array{id: int, url: string}
     */
    public function asArray(): array
    {
        return [
            self::ID => $this->id,
            self::URL => $this->url,
        ];
    }

    public function id(): int
    {
        return $this->id;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function jsonSerialize(): array
    {
        return $this->asArray();
    }
}
