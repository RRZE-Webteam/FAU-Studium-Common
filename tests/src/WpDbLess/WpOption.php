<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\WpDbLess;

class WpOption
{
    public function __construct(private array $options = [])
    {
    }

    public function addOption(string $optionName, mixed $optionValue): void
    {
        $this->options[$optionName] = $optionValue;
    }

    public function options(): array
    {
        return $this->options;
    }
}
