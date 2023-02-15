<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Queue;

use Fau\DegreeProgram\Common\Application\Queue\Message;
use Fau\DegreeProgram\Common\Application\Queue\MessageBus;
use Fau\DegreeProgram\Common\Application\Queue\MessageHandler;

final class SyncMessageBus implements MessageBus
{
    public function __construct(private MessageHandler $messageHandler)
    {
    }

    public function dispatch(Message $message): void
    {
        $this->messageHandler->handle($message);
    }
}
