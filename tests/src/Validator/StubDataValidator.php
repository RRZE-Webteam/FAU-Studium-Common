<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Validator;

use Fau\DegreeProgram\Common\Domain\DegreeProgramDataValidator;
use Fau\DegreeProgram\Common\Domain\Violations;

final class StubDataValidator implements DegreeProgramDataValidator
{
    public function __construct(
        private Violations $result
    ) {
    }

    public function validate(array $data): Violations
    {
        return $this->result;
    }
}
