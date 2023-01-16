<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Dto;

final class ListItemDto
{
    private string $url;
    private string $title;

    public function __construct(string $url, string $title)
    {

        $this->url = $url;
        $this->title = $title;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function title(): string
    {
        return $this->title;
    }
}
