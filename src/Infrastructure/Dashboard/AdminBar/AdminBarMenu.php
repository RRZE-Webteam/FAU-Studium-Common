<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Dashboard\AdminBar;

use WP_Admin_Bar;

final class AdminBarMenu
{
    public const PARENT_ID = 'fau-degree-program';

    /**
     * @param array<MenuItem> $menuItems
     */
    private function __construct(private array $menuItems)
    {
    }

    public static function new(): self
    {
        return new self([]);
    }

    public function withMenuItem(MenuItem $item): self
    {
        $this->menuItems[] = $item;
        return new self($this->menuItems);
    }

    public function render(WP_Admin_Bar $adminBar): void
    {
        $adminBar->add_menu(
            [
                'id' => self::PARENT_ID,
                'title' => _x(
                    'FAU Degree Program',
                    'backoffice: admin bar menu item',
                    'fau-degree-program-common'
                ),
                'href' => '#',
            ]
        );

        foreach ($this->menuItems as $menuItem) {
            $adminBar->add_menu(
                [
                    'id' => self::PARENT_ID . '-' . $menuItem->id(),
                    'parent' => self::PARENT_ID,
                    'title' => $menuItem->title(),
                    'href' => $menuItem->href(),
                ]
            );
        }
    }
}
