<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Queue;

use Fau\DegreeProgram\Common\Application\JsonSerializer\JsonSerializer;
use Fau\DegreeProgram\Common\Application\Queue\Message;
use Fau\DegreeProgram\Common\Application\Queue\MessageBus;
use Fau\DegreeProgram\Common\Application\Queue\MessageHandler;

final class WpCronMessageBus implements MessageBus
{
    public function __construct(private JsonSerializer $jsonSerializer)
    {
    }

    public function dispatch(Message $message): void
    {
        wp_schedule_single_event(
            time(),
            MessageHandler::ID,
            [$this->jsonSerializer->serialize($message)]
        );
    }
}
