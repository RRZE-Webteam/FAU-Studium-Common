<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Dashboard\AdminBar;

final class MenuItem
{
    private function __construct(
        private string $id,
        private string $title,
        private string $href
    ) {
    }

    public static function new(
        string $id,
        string $title,
        string $href
    ): self {

        return new self($id, $title, $href);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function href(): string
    {
        return $this->href;
    }
}
