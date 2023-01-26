<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Sanitizer;

use Fau\DegreeProgram\Common\Domain\DegreeProgramSanitizer;

final class StubSanitizer implements DegreeProgramSanitizer
{
    public function __construct(
        private string $prefix = ''
    ) {
    }

    public function sanitizeContentField(string $content): string
    {
        return $this->prefix . $content;
    }
}
