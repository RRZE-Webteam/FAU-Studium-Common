<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

/**
 * @psalm-type RelatedDegreeProgramType = array{
 *     id: int,
 *     title: string,
 *     url: string,
 * }
 */
final class RelatedDegreeProgram
{
    public const ID = 'id';
    public const TITLE = 'title';
    public const URL = 'url';

    private function __construct(
        private int $id,
        private string $title,
        private string $url,
    ) {
    }

    public static function new(
        int $id,
        string $title,
        string $url,
    ): self {

        return new self(
            $id,
            $title,
            $url,
        );
    }

    public function title(): string
    {
        return $this->title;
    }

    public function url(): string
    {
        return $this->url;
    }

    /**
     * @psalm-return RelatedDegreeProgramType
     */
    public function asArray(): array
    {
        return [
            self::ID => $this->id,
            self::TITLE => $this->title,
            self::URL => $this->url,
        ];
    }
}
