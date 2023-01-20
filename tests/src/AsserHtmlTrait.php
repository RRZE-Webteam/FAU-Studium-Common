<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests;

trait AsserHtmlTrait
{
    protected function assertHtmlEqual(string $expected, string $actual): void
    {
        $this->assertSame(
            $this->normalizeSpaces($expected),
            $this->normalizeSpaces($actual)
        );
    }

    private function normalizeSpaces(string $html): string
    {
        return (string) preg_replace('/[\s]{2,}|\n/', '', $html);
    }
}
