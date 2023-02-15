<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\JsonSerializer;

use LogicException;

final class CouldNotDeserialize extends LogicException
{
    public static function becauseClassNotImplementJsonSerializableInterface(string $className): self
    {
        return new self(sprintf(
            'Class %s must implement %s interface',
            $className,
            JsonSerializable::class
        ));
    }

    public static function becauseWrongClassInstantiated(mixed $message, string $className): self
    {
        return new self(sprintf(
            'Message must be instance of %s, %s given',
            $className,
            is_object($message) ? get_class($message) : gettype($message)
        ));
    }
}
