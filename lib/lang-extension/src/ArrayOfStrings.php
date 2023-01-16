<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\LanguageExtension;

use ArrayObject;

/**
 * @template-extends ArrayObject<array-key, string>
 */
final class ArrayOfStrings extends ArrayObject
{
    private function __construct(string ...$strings)
    {
        parent::__construct($strings);
    }

    public static function new(string ...$strings): self
    {
        return new self(...$strings);
    }
}
