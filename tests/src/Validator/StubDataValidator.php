<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Validator;

use Fau\DegreeProgram\Common\Domain\DegreeProgramDataValidator;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;

final class StubDataValidator implements DegreeProgramDataValidator
{
    public function __construct(
        private ArrayOfStrings $result
    ) {
    }

    public function validate(array $data): ArrayOfStrings
    {
        return $this->result;
    }
}
