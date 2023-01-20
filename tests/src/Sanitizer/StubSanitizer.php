<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Sanitizer;

use Fau\DegreeProgram\Common\Domain\DegreeProgramSanitizer;

final class StubSanitizer implements DegreeProgramSanitizer
{
    public function sanitizeContentField(string $content): string
    {
        return $content;
    }
}
