<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Queue;

interface MessageBus
{
    public function dispatch(Message $message): void;
}
