<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\JsonSerializer;

interface JsonSerializable extends \JsonSerializable
{
    public const FROM_ARRAY_METHOD = 'fromArray';

    public static function fromArray(array $array): static;
}
