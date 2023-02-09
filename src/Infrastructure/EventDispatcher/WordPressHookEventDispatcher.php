<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Stringable;

final class WordPressHookEventDispatcher implements EventDispatcherInterface
{
    public function dispatch(object $event): void
    {
        $action = $event instanceof Stringable ? (string) $event : $event::class;

        do_action($action, $event);
    }
}
