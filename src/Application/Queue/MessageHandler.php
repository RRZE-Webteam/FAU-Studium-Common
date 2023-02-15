<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Queue;

use Fau\DegreeProgram\Common\Application\JsonSerializer\JsonSerializer;

final class MessageHandler
{
    public const ID = 'fau_message_handler';

    /**
     * @param array<class-string,array<callable(Message): void>> $handlers
     */
    public function __construct(
        private JsonSerializer $serializer,
        private iterable $handlers
    ) {
    }

    public function handle(string|Message $message): void
    {
        /** @var Message $messageObject */
        $messageObject = is_string($message) ? $this->serializer->deserialize($message) : $message;
        $handlers = $this->provide($messageObject);

        foreach ($handlers as $handler) {
            $handler($messageObject);
        }
    }

    /**
     * @return array<callable(Message): void>
     */
    private function provide(Message $message): iterable
    {
        return $this->handlers[get_class($message)] ?? [];
    }
}
