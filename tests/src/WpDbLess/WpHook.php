<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\WpDbLess;

class WpHook
{
    public function addHook(string $hook, callable $callback, int $priority = 10): void
    {
        if (function_exists('add_filter')) {
            add_filter($hook, $callback, $priority);
            return;
        }

        $GLOBALS['wp_filter'] = $GLOBALS['wp_filter'] ?? [];
        $GLOBALS['wp_filter'][$hook] = $GLOBALS['wp_filter'][$hook] ?? [];
        $GLOBALS['wp_filter'][$hook][$priority] = $GLOBALS['wp_filter'][$hook][$priority] ?? [];
        $GLOBALS['wp_filter'][$hook][$priority][] = [
            'accepted_args' => PHP_INT_MAX,
            'function' => $callback,
        ];
    }
}
