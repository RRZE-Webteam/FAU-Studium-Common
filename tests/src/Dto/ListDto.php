<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Dto;

final class ListDto
{
    private string $title;
    /**
     * @var ListItemDto[]
     */
    private array $listItems;

    public function __construct(string $title, ListItemDto ...$listItems)
    {

        $this->title = $title;
        $this->listItems = $listItems;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function listItems(): array
    {
        return $this->listItems;
    }
}
